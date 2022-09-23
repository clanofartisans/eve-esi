<?php

namespace Clanofartisans\EveEsi\Jobs\Handlers;

use Clanofartisans\EveEsi\Facades\EveESI as ESI;
use Clanofartisans\EveEsi\Jobs\Handlers\Concerns\HasIndex;
use Clanofartisans\EveEsi\Models\Station;
use Clanofartisans\EveEsi\Routes\ESIRoute;
use Illuminate\Support\Collection;

class Stations extends ESIHandler
{
    use HasIndex;

    /**
     * The Eloquent model associated with this handler.
     *
     * @var string
     */
    public string $dataModel = Station::class;

    /**
     * The name of the ID field as retrieved from ESI.
     *
     * @var string
     */
    public string $esiIDName = 'station_id';

    /**
     * The internal name of the table associated with this handler.
     *
     * @var string
     */
    public string $updateTable = 'stations';

    /**
     * New - Specific override for this handler
     *
     * @return Collection
     */
    protected function fetchIndex(): Collection
    {
        return Station::all('station_id')->pluck('station_id');
    }

    /**
     * New - Per Handler, note that this isn't actually a valid route
     *
     * @return ESIRoute
     */
    protected function indexRoute(): ESIRoute
    {
        return ESI::universe()->stations();
    }

    /**
     * New - Per Handler
     *
     * @param int $id
     * @return ESIRoute
     */
    protected function resourceRoute(int $id): ESIRoute
    {
        return ESI::universe()->stations()->station($id);
    }
}
