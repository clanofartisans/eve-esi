<?php

namespace Clanofartisans\EveEsi\Jobs\Handlers;

use Clanofartisans\EveEsi\Facades\EveESI as ESI;
use Clanofartisans\EveEsi\Jobs\Handlers\Concerns\HasIndex;
use Clanofartisans\EveEsi\Models\Constellation;
use Clanofartisans\EveEsi\Models\ESITableUpdates;
use Clanofartisans\EveEsi\Models\Station;
use Clanofartisans\EveEsi\Models\System;
use Clanofartisans\EveEsi\Routes\ESIRoute;
use Illuminate\Support\Arr;

class Systems extends ESIHandler
{
    use HasIndex;

    /**
     * The Eloquent model associated with this handler.
     *
     * @var string
     */
    public string $dataModel = System::class;

    /**
     * The name of the ID field as retrieved from ESI.
     *
     * @var string
     */
    public string $esiIDName = 'system_id';

    /**
     * The internal name of the table associated with this handler.
     *
     * @var string
     */
    public string $updateTable = 'systems';

    /**
     * New
     *
     * @param string $section
     * @return void
     */
    public function specialData(string $section): void
    {
        $this->specialPopulateStationIDs();
        $this->specialSetRegionIDs();
        $this->clearTableUpdates();

        parent::specialData($section);
    }

    /**
     * New - Note override to delay clearing table updates
     *
     * @param string $section
     * @param array $ids
     * @return void
     */
    public function upsertData(string $section, array $ids): void
    {
        $updates = ESITableUpdates::whereIntegerInRaw('id', $ids)
            ->get(['data_id', 'data', 'hash']);

        $model = new $this->dataModel;

        $model->createFromJson($section, $updates);
    }

    /**
     * New - Per Handler
     *
     * @return ESIRoute
     */
    protected function indexRoute(): ESIRoute
    {
        return ESI::universe()->systems();
    }

    /**
     * New - Per Handler
     *
     * @param int $id
     * @return ESIRoute
     */
    protected function resourceRoute(int $id): ESIRoute
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
            ->where('section', $this->section)
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
