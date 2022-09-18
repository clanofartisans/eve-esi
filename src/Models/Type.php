<?php

namespace Clanofartisans\EveEsi\Models;

class Type extends ESIModel
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'esi_types';

    /**
     * The primary key for the model.
     *
     * @var string
     */
    protected $primaryKey = 'type_id';

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
        $data = $this->clean($data, [
            'capacity',
            'graphic_id',
            'icon_id',
            'market_group_id',
            'mass',
            'packaged_volume',
            'portion_size',
            'radius',
            'volume'
        ]);

        return $this->updateOrCreate([
            'type_id' => $id
        ], [
            'group_id' => $data['group_id'],
            'name' => $data['name'],
            'description' => $data['description'],
            'capacity' => $data['capacity'],
            'graphic_id' => $data['graphic_id'],
            'icon_id' => $data['icon_id'],
            'market_group_id' => $data['market_group_id'],
            'mass' => $data['mass'],
            'packaged_volume' => $data['packaged_volume'],
            'portion_size' => $data['portion_size'],
            'radius' => $data['radius'],
            'volume' => $data['volume'],
            'published' => $data['published'],
            'hash' => $hash
        ]);
    }
}
