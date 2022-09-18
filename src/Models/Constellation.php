<?php

namespace Clanofartisans\EveEsi\Models;

class Constellation extends ESIModel
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'esi_constellations';

    /**
     * The primary key for the model.
     *
     * @var string
     */
    protected $primaryKey = 'constellation_id';

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
            'constellation_id' => $id
        ], [
            'region_id' => $data['region_id'],
            'name' => $data['name'],
            'position_x' => $data['position']['x'],
            'position_y' => $data['position']['y'],
            'position_z' => $data['position']['z'],
            'hash' => $hash
        ]);
    }
}
