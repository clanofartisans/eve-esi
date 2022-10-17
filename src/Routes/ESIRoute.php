<?php

namespace Clanofartisans\EveEsi\Routes;

use Clanofartisans\EveEsi\Auth\RefreshTokenException;
use Illuminate\Http\Client\PendingRequest;
use Illuminate\Http\Client\Response;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Redis;
use Clanofartisans\EveEsi\Auth\User;

abstract class ESIRoute
{
    /**
     * The current auth instance that will be used for the request, if applicable.
     *
     * @var User
     */
    protected User $auth;

    /**
     * The base ESI URI where API calls will be made.
     *
     * @var string
     */
    protected string $baseURI = 'https://esi.evetech.net/latest';

    /**
     * The path and query parameters that will be used to build the request.
     *
     * @var array
     */
    protected array $parameters = ['path' => [], 'query' => []];

    /**
     * The name of the current API route.
     *
     * @var string
     */
    protected string $route = '';

    /**
     * Adds the current auth instance to the request.
     *
     * @param int $characterID
     * @return ESIRoute $this
     */
    public function auth(int $characterID = 0): ESIRoute
    {
        if($characterID === 0) {
            $characterID = config('eve-esi.auth_character_id');
        }

        $auth = User::where('character_id', $characterID)->firstOrFail();

        $this->auth = $auth;

        return $this;
    }

    /**
     * Send the request to the ESI API and return the response.
     *
     * @param bool $force
     * @return Response|bool
     * @throws InvalidESIResponseException
     * @throws RefreshTokenException
     */
    public function get(bool $force = true): Response|bool
    {
        // Set the User Agent to something nice so CCP can contact us, just in case
        $request = Http::withUserAgent(config('eve-esi.user_agent'));

        // Force the request to ignore ETags/caching if $force is set to true
        // Otherwise, we'll apply our cached ETag to the request so we can
        // more easily determine if the response contains updated data.
        if(!$force) {
            $request = $request->withHeaders([
                'If-None-Match' => $this->etag()
            ]);
        }

        // Apply the current auth instance, if set
        if(isset($this->auth)) {
            $request->withToken($this->auth->token);
        }

        // Send the request
        $response = $this->sendRequest($request);

        // If the token is expired, refresh it and re-send the request
        if($this->tokenExpired($response)) {
            $this->auth->refreshToken();
            $request->withToken($this->auth->token);
            $response = $this->sendRequest($request);
        }

        // We already have the most recent data for this request
        if($this->isCurrent($response)) {
            return false;
        }

        // Handle other invalid responses by throwing an Exception
        if(!$this->isValid($response)) {
            throw new InvalidESIResponseException();
        }

        // Update the ETag cache for this request
        Redis::set($this->redisKey(), head($response->getHeader('etag')));

        return $response;
    }

    /**
     * Get the number of pages available for a given ESI resource.
     *
     * @return int
     */
    public function getNumPages(): int
    {
        $response = Http::head($this->uri(), $this->parameters['query']);

        return !empty($response->header('X-Pages')) ? (int) $response->header('X-Pages') : 1;
    }

    /**
     * Set the desired page number for our request.
     *
     * @param int $page
     * @return ESIRoute $this
     */
    public function page(int $page): ESIRoute
    {
        $this->parameters['query']['page'] = $page;

        return $this;
    }

    /**
     * Retrieves the cached ETag for the request.
     *
     * @return string|null
     */
    protected function etag(): string|null
    {
        return Redis::get($this->redisKey());
    }

    /**
     * Detects if the response matched what's in our cache.
     *
     * @param Response $response
     * @return bool
     */
    protected function isCurrent(Response $response): bool
    {
        return $response->status() === 304;
    }

    /**
     * Detects if the response is "OK" or not.
     *
     * @param Response $response
     * @return bool
     */
    protected function isValid(Response $response): bool
    {
        return $response->status() === 200;
    }

    /**
     * Constructs the Redis key that will be used for caching the request.
     *
     * @return string
     */
    protected function redisKey(): string
    {
        $key  = 'esi:';
        $key .= $this->route;
        foreach($this->parameters['path'] as $path) {
            $key .= ':'.$path;
        }
        foreach($this->parameters['query'] as $query) {
            $key .= ':'.$query;
        }

        return $key;
    }

    /**
     * Sends the HTTP request to the EVE ESI API.
     *
     * @param PendingRequest $request
     * @return Response
     */
    protected function sendRequest(PendingRequest $request): Response
    {
        return $request->get($this->uri(), $this->parameters['query']);
    }

    /**
     * Determines if the auth token being used has expired.
     *
     * @param Response $response
     * @return bool
     */
    protected function tokenExpired(Response $response): bool
    {
        return ($response->status() === 403 && $response->json()['error'] === 'token is expired');
    }

    /**
     * Builds the URI for the request.
     *
     * @return string
     */
    abstract protected function uri(): string;
}
