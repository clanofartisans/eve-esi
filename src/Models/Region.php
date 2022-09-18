<?php

namespace Clanofartisans\EveEsi\Models;

class Region extends ESIModel
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'esi_regions';

    /**
     * The primary key for the model.
     *
     * @var string
     */
    protected $primaryKey = 'region_id';

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
        $data = $this->clean($data, ['description']);

        return $this->updateOrCreate([
            'region_id' => $id
        ], [
            'description' => $data['description'],
            'name' => $data['name'],
            'hash' => $hash
        ]);
    }
}
