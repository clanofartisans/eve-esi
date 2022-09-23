<?php

namespace Clanofartisans\EveEsi\Jobs\Handlers;

use Clanofartisans\EveEsi\Facades\EveESI as ESI;
use Clanofartisans\EveEsi\Jobs\Handlers\Concerns\HasIndex;
use Clanofartisans\EveEsi\Models\Type;
use Clanofartisans\EveEsi\Routes\ESIRoute;

class Types extends ESIHandler
{
    use HasIndex;

    /**
     * The Eloquent model associated with this handler.
     *
     * @var string
     */
    public string $dataModel = Type::class;

    /**
     * The name of the ID field as retrieved from ESI.
     *
     * @var string
     */
    public string $esiIDName = 'type_id';

    /**
     * The internal name of the table associated with this handler.
     *
     * @var string
     */
    public string $updateTable = 'types';

    /**
     * New - Per Handler
     *
     * @return ESIRoute
     */
    protected function indexRoute(): ESIRoute
    {
        return ESI::universe()->types();
    }

    /**
     * New - Per Handler
     *
     * @param int $id
     * @return ESIRoute
     */
    protected function resourceRoute(int $id): ESIRoute
    {
        return ESI::universe()->types()->type($id);
    }
}
