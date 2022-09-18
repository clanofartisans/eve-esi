<?php

namespace Clanofartisans\EveEsi\Jobs\Handlers;

use Clanofartisans\EveEsi\Auth\RefreshTokenException;
use Clanofartisans\EveEsi\Facades\EveESI as ESI;
use Clanofartisans\EveEsi\Jobs\Handlers\Contracts\HasResourceListRoute;
use Clanofartisans\EveEsi\Jobs\Handlers\Contracts\HasSingleResourceRoute;
use Clanofartisans\EveEsi\Models\Constellation;
use Clanofartisans\EveEsi\Models\ESITableUpdates;
use Clanofartisans\EveEsi\Models\Station;
use Clanofartisans\EveEsi\Models\System;
use Clanofartisans\EveEsi\Routes\ESIRoute;
use Clanofartisans\EveEsi\Routes\InvalidESIResponseException;
use Illuminate\Support\Arr;

class Systems extends ESIHandler implements HasResourceListRoute, HasSingleResourceRoute
{
    /**
     * The internal name of the table associated with this handler.
     *
     * @var string
     */
    public string $updateTable = 'systems';

    /**
     * The internal name of the table section associated with this handler.
     *
     * @var string
     */
    public string $updateSection = '*';

    /**
     * The Eloquent model associated with this handler.
     *
     * @var string
     */
    public string $dataModel = System::class;

    /**
     * Retrieves and returns a list of record IDs from the ESI API.
     *
     * @return array
     * @throws InvalidESIResponseException
     * @throws RefreshTokenException
     */
    public function fetchIDs(): array
    {
        return ESI::universe()->systems()->get()->json();
    }

    /**
     * Handles cleanup and any special post-processing after the data has been upserted.
     *
     * @param string $section
     * @return void
     */
    public function postProcessing(string $section = '*'): void
    {
        $this->specialPopulateStationIDs();
        $this->specialSetRegionIDs();
        $this->cleanupTableUpdates($section);
    }

    /**
     * Returns the route pointing to a single resource for this handler.
     *
     * @param int $id
     * @return ESIRoute
     */
    public function resourceRoute(int $id): ESIRoute
    {
        return ESI::universe()->systems()->system($id);
    }

    /**
     * Retrieves Station IDs from the current System data and adds them to the Stations table.
     *
     * @return void
     */
    protected function specialPopulateStationIDs(): void
    {
        $list = ESITableUpdates::where('table', $this->updateTable)
            ->where('section', $this->updateSection)
            ->pluck('id');

        foreach($list as $id) {
            $system = ESITableUpdates::find($id);
            $stations = Arr::exists($system->data, 'stations') ? $system->data['stations'] : [];
            foreach ($stations as $station) {
                Station::insertOrIgnore([
                    'system_id' => $system->data['system_id'],
                    'station_id' => $station
                ]);
            }
        }
    }

    /**
     * Retrieves Region IDs from the System's Constellation and updates the System record.
     *
     * @return void
     */
    protected function specialSetRegionIDs(): void
    {
        $constellations = Constellation::all();

        foreach($constellations as $constellation) {
            System::where('constellation_id', $constellation->constellation_id)
                ->update(['region_id' => $constellation->region_id]);
        }
    }
}

