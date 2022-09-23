<?php

namespace Clanofartisans\EveEsi\Models;

use Illuminate\Support\Collection;

class Station extends ESIModel
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'esi_stations';

    /**
     * The primary key for the model.
     *
     * @var string
     */
    protected $primaryKey = 'station_id';

    /**
     * Creates a record, or updates an existing record, from JSON data.
     *
     * @param string $section
     * @param Collection $updates
     * @return void
     */
    public function createFromJson(string $section, Collection $updates): void
    {
        foreach($updates as $update) {
            $this->upsert([
                'station_id' => $update->data_id,
                'system_id' => $update->data['system_id'],
                'name' => $update->data['name'],
                'max_dockable_ship_volume' => $update->data['max_dockable_ship_volume'],
                'office_rental_cost' => $update->data['office_rental_cost'],
                'owner' => $update->data['owner'],
                'position_x' => $update->data['position']['x'],
                'position_y' => $update->data['position']['y'],
                'position_z' => $update->data['position']['z'],
                'race_id' => $update->data['race_id'],
                'reprocessing_efficiency' => $update->data['reprocessing_efficiency'],
                'reprocessing_stations_take' => $update->data['reprocessing_stations_take'],
                'type_id' => $update->data['type_id'],
                'hash' => $update->hash
            ], ['station_id'], [
                'system_id',
                'name',
                'max_dockable_ship_volume',
                'office_rental_cost',
                'owner',
                'position_x',
                'position_y',
                'position_z',
                'race_id',
                'reprocessing_efficiency',
                'reprocessing_stations_take',
                'type_id',
                'hash'
            ]);
        }
    }

    /**
     * New
     *
     * @param string $section
     * @return $this
     */
    public function whereSection(string $section)
    {
        return $this;
    }
}
