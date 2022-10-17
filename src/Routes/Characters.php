<?php

namespace Clanofartisans\EveEsi\Routes;

class Characters extends ESIRoute
{
    /**
     * Handles the /characters/{character_id} path.
     *
     * @param int $characterID
     * @return Characters $this
     */
    public function character(int $characterID): Characters
    {
        $this->route = 'characters.character';

        $this->parameters['path']['character_id'] = $characterID;

        return $this;
    }

    /**
     * Calls the appropriate /{character_id} endpoint.
     *
     * @return Characters $this
     */
    public function location(): Characters
    {
        return $this->characterLocation();
    }

    /**
     * Handles the /characters/{character_id}/location path.
     *
     * @return Characters $this
     */
    protected function characterLocation(): Characters
    {
        $this->route = 'characters.character.location';

        return $this;
    }

    /**
     * Builds the URI for the request.
     *
     * @return string
     */
    protected function uri(): string
    {
        $uri = match ($this->route) {
            'characters.character' =>
                '/characters/' . $this->parameters['path']['character_id'],
            'characters.character.location' =>
                '/characters/' . $this->parameters['path']['character_id'] . '/location',
            default => ''
        };

        return $this->baseURI.$uri;
    }
}
