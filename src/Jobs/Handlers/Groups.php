<?php

namespace Clanofartisans\EveEsi\Jobs\Handlers;

use Clanofartisans\EveEsi\Auth\RefreshTokenException;
use Clanofartisans\EveEsi\Facades\EveESI as ESI;
use Clanofartisans\EveEsi\Jobs\Handlers\Contracts\HasResourceListRoute;
use Clanofartisans\EveEsi\Jobs\Handlers\Contracts\HasSingleResourceRoute;
use Clanofartisans\EveEsi\Models\Group;
use Clanofartisans\EveEsi\Routes\ESIRoute;
use Clanofartisans\EveEsi\Routes\InvalidESIResponseException;

class Groups extends ESIHandler implements HasResourceListRoute, HasSingleResourceRoute
{
    /**
     * The internal name of the table associated with this handler.
     *
     * @var string
     */
    public string $updateTable = 'groups';

    /**
     * The Eloquent model associated with this handler.
     *
     * @var string
     */
    public string $dataModel = Group::class;

    /**
     * Retrieves and returns a list of record IDs from the ESI API.
     *
     * @return array
     * @throws InvalidESIResponseException
     * @throws RefreshTokenException
     */
    public function fetchIDs(): array
    {
        return ESI::universe()->groups()->getAllPages();
    }

    /**
     * Returns the route pointing to a single resource for this handler.
     *
     * @param int $id
     * @return ESIRoute
     */
    public function resourceRoute(int $id): ESIRoute
    {
        return ESI::universe()->groups()->group($id);
    }
}
