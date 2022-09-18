<?php

namespace Clanofartisans\EveEsi\Models;

class Structure extends ESIModel
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'esi_structures';

    /**
     * The primary key for the model.
     *
     * @var string
     */
    protected $primaryKey = 'structure_id';

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
            'structure_id' => $id
        ], [
            'system_id' => $data['solar_system_id'],
            'name' => $data['name'],
            'owner_id' => $data['owner_id'],
            'position_x' => $data['position']['x'],
            'position_y' => $data['position']['y'],
            'position_z' => $data['position']['z'],
            'type_id' => $data['type_id'],
            'hash' => $hash
        ]);
    }
}
