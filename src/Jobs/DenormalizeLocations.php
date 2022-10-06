<?php

namespace Clanofartisans\EveEsi\Jobs;

use Clanofartisans\EveEsi\Models\Location;
use Clanofartisans\EveEsi\Models\Station;
use Clanofartisans\EveEsi\Models\Structure;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class DenormalizeLocations implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * New
     *
     * @return void
     */
    public function handle(): void
    {
        $locations = [];
        foreach(Station::lazy() as $station) {
            $locations[] = [
                'system_id' => $station->system_id,
                'location_type' => 'station',
                'location_id' => $station->station_id,
                'name' => $station->name,
                'security_status' => $this->calculateSecurity($station->security_status)
            ];
        }

        foreach(Structure::lazy() as $structure) {
            $locations[] = [
                'system_id' => $structure->system_id,
                'location_type' => 'structure',
                'location_id' => $structure->structure_id,
                'name' => $structure->name,
                'security_status' => $this->calculateSecurity($structure->security_status)
            ];
        }

        foreach($locations as $location) {
            Location::upsert([
                'system_id' => $location['system_id'],
                'location_type' => $location['location_type'],
                'location_id' => $location['location_id'],
                'name' => $location['name'],
                'security_status' => $location['security_status']
            ], ['location_id'], [
                'system_id',
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
