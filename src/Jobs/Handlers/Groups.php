<?php

namespace Clanofartisans\EveEsi\Jobs\Handlers;

use Clanofartisans\EveEsi\Facades\EveESI as ESI;
use Clanofartisans\EveEsi\Jobs\Handlers\Concerns\HasIndex;
use Clanofartisans\EveEsi\Models\Group;
use Clanofartisans\EveEsi\Routes\ESIRoute;

class Groups extends ESIHandler
{
    use HasIndex;

    /**
     * The Eloquent model associated with this handler.
     *
     * @var string
     */
    public string $dataModel = Group::class;

    /**
     * The name of the ID field as retrieved from ESI.
     *
     * @var string
     */
    public string $esiIDName = 'group_id';

    /**
     * The internal name of the table associated with this handler.
     *
     * @var string
     */
    public string $updateTable = 'groups';

    /**
     * New - Per Handler
     *
     * @return ESIRoute
     */
    protected function indexRoute(): ESIRoute
    {
        return ESI::universe()->groups();
    }

    /**
     * New - Per Handler
     *
     * @param int $id
     * @return ESIRoute
     */
    protected function resourceRoute(int $id): ESIRoute
    {
        return ESI::universe()->groups()->group($id);
    }
}
