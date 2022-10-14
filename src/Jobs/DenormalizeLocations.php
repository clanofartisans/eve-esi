<?php

namespace Clanofartisans\EveEsi\Jobs;

use Clanofartisans\EveEsi\Models\Location;
use Clanofartisans\EveEsi\Models\MarketOrder;
use Clanofartisans\EveEsi\Models\Region;
use Clanofartisans\EveEsi\Models\Station;
use Clanofartisans\EveEsi\Models\Structure;
use Clanofartisans\EveEsi\Models\System;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class DenormalizeLocations implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * The number of seconds the job can run before timing out.
     *
     * @var int
     */
    public int $timeout = 300;

    /**
     * New
     *
     * @return void
     */
    public function handle(): void
    {
        $locations = [];

        // Get all Regions
        $regions = Region::select(['region_id', 'name'])->get();

        // Get all Systems
        $systems = System::select(['region_id', 'system_id', 'name', 'security_status'])->get();

        // Get Stations
        foreach(Station::lazy() as $station) {
            $system = $systems->find($station->system_id);
            $region = $regions->find($system->region_id);
            $locations[$station->station_id] = [
                'region_id' => $region->region_id,
                'region_name' => $region->name,
                'system_id' => $system->system_id,
                'system_name' => $system->name,
                'location_type' => 'station',
                'location_id' => $station->station_id,
                'name' => $station->name,
                'security_status' => $this->calculateSecurity($system->security_status)
            ];
        }

        // Get Structures
        foreach(Structure::lazy() as $structure) {
            $system = $systems->find($structure->system_id);
            $region = $regions->find($system->region_id);
            $locations[$structure->structure_id] = [
                'region_id' => $region->region_id,
                'region_name' => $region->name,
                'system_id' => $system->system_id,
                'system_name' => $system->name,
                'location_type' => 'structure',
                'location_id' => $structure->structure_id,
                'name' => $structure->name,
                'security_status' => $this->calculateSecurity($system->security_status)
            ];
        }

        // Get unknown Structures from Market Orders
        $markets = MarketOrder::select(['region_id', 'system_id', 'location_id'])
            ->where('location_id', '>', 1000000000000)
            ->distinct()
            ->get();

        // Process unknown Structures
        foreach($markets as $market) {
            if(!isset($locations[$market->location_id])) {
                $system = $systems->find($market->system_id);
                $region = $regions->find($system->region_id);
                $unknown = [
                    'region_id' => $region->region_id,
                    'region_name' => $region->name,
                    'system_id' => $system->system_id,
                    'system_name' => $system->name,
                    'location_type' => 'structure',
                    'location_id' => $market->location_id,
                    'name' => $system->name . ' - Unknown Structure',
                    'security_status' => $unknown['security_status'] = $this->calculateSecurity($system->security_status)
                ];
                $locations[$market->location_id] = $unknown;
            }
        }

        // Upsert all Locations
        foreach($locations as $location) {
            Location::upsert([
                'region_id' => $location['region_id'],
                'region_name' => $location['region_name'],
                'system_id' => $location['system_id'],
                'system_name' => $location['system_name'],
                'location_type' => $location['location_type'],
                'location_id' => $location['location_id'],
                'name' => $location['name'],
                'security_status' => $location['security_status']
            ], ['location_id'], [
                'region_id',
                'region_name',
                'system_id',
                'system_name',
                'location_type',
                'name',
                'security_status'
            ]);
        }
    }

    /**
     * New
     *
     * @param $security
     * @return float
     */
    protected function calculateSecurity($security): float {
        if($security > 0 && $security < 0.05) {
            return 0.1;
        }

        return round($security, 1);
    }
}
