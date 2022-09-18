<?php

namespace Clanofartisans\EveEsi\Models;

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
     * @param int $id
     * @param array $data
     * @param string $hash
     * @return ESIModel
     */
    public function createFromJson(int $id, array $data, string $hash): ESIModel
    {
        return $this->updateOrCreate([
            'station_id' => $id
        ], [
            'name' => $data['name'],
            'max_dockable_ship_volume' => $data['max_dockable_ship_volume'],
            'office_rental_cost' => $data['office_rental_cost'],
            'owner' => $data['owner'],
            'position_x' => $data['position']['x'],
            'position_y' => $data['position']['y'],
            'position_z' => $data['position']['z'],
            'race_id' => $data['race_id'],
            'reprocessing_efficiency' => $data['reprocessing_efficiency'],
            'reprocessing_stations_take' => $data['reprocessing_stations_take'],
            'type_id' => $data['type_id'],
            'hash' => $hash
        ]);
    }
}
