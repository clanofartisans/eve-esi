<?php

namespace Clanofartisans\EveEsi\Auth;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Support\Facades\Http;

/**
 * @property string $token
 */
class User extends Authenticatable
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'character_id',
        'character_owner_hash',
        'character_name',
        'token',
        'refreshToken',
        'expiresIn'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<string>
     */
    protected $hidden = [
        'token',
        'refreshToken'
    ];

    /**
     * The ESI URI where auth tokens are refreshed.
     *
     * @var string
     */
    protected string $refreshURI = 'https://login.eveonline.com/v2/oauth/token';

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'yams_users';

    /**
     * Refreshes the auth token for the configured character.
     *
     * @return bool
     * @throws RefreshTokenException
     */
    public function refreshToken(): bool
    {
        // Use the stored refresh token to get new auth tokens
        $response = Http::withBasicAuth(config('eve-esi.client_id'), config('eve-esi.client_secret'))
            ->asForm()
            ->post($this->refreshURI, [
                'grant_type' => 'refresh_token',
                'refresh_token' => $this->refreshToken
            ]);

        // If we got a good response, store the updated auth tokens
        if($response->status() === 200) {
            $this->token = $response->json()['access_token'];
            $this->expiresIn = $response->json()['expires_in'];
            $this->refreshToken = $response->json()['refresh_token'];

            $this->save();

            return true;
        }

        // We didn't get a good response so something weird happened
        throw new RefreshTokenException();
    }
}
