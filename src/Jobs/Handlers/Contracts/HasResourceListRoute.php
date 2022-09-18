<?php

namespace Clanofartisans\EveEsi\Jobs\Handlers\Contracts;

use Clanofartisans\EveEsi\Auth\RefreshTokenException;

interface HasResourceListRoute
{
    /**
     * Retrieves and returns a list of record IDs from the ESI API.
     *
     * @return array
     * @throws RefreshTokenException
     */
    public function fetchIDs(): array;
}
