<?php

namespace Clanofartisans\EveEsi\Jobs\Handlers;

use Clanofartisans\EveEsi\Facades\EveESI as ESI;
use Clanofartisans\EveEsi\Jobs\Handlers\Concerns\NoIndex;
use Clanofartisans\EveEsi\Models\Ancestry;
use Clanofartisans\EveEsi\Routes\ESIRoute;

class Ancestries extends ESIHandler
{
    use NoIndex;

    /**
     * The Eloquent model associated with this handler.
     *
     * @var string
     */
    public string $dataModel = Ancestry::class;

    /**
     * The name of the ID field as retrieved from ESI.
     *
     * @var string
     */
    public string $esiIDName = 'id';

    /**
     * The internal name of the table associated with this handler.
     *
     * @var string
     */
    public string $updateTable = 'ancestries';

    /**
     * New - Per Handler
     *
     * @return ESIRoute
     */
    protected function baseRoute(): ESIRoute
    {
        return ESI::universe()->ancestries();
    }
}
