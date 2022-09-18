<?php

namespace Clanofartisans\EveEsi\Models;

class System extends ESIModel
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'esi_systems';

    /**
     * The primary key for the model.
     *
     * @var string
     */
    protected $primaryKey = 'system_id';

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
        $data = $this->clean($data, ['security_class', 'star_id']);

        return $this->updateOrCreate([
            'system_id' => $id
        ], [
            'constellation_id' => $data['constellation_id'],
            'name' => $data['name'],
            'position_x' => $data['position']['x'],
            'position_y' => $data['position']['y'],
            'position_z' => $data['position']['z'],
            'security_class' => $data['security_class'],
            'security_status' => $data['security_status'],
            'star_id' => $data['star_id'],
            'hash' => $hash
        ]);
    }
}
