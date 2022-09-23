<?php

namespace Clanofartisans\EveEsi\Models;

use Illuminate\Support\Collection;

class Type extends ESIModel
{
    /**
     * The primary key for the model.
     *
     * @var string
     */
    protected $primaryKey = 'type_id';

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'esi_types';

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
            $update->data = $this->clean($update->data, [
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

            $this->upsert([
                'type_id' => $update->data_id,
                'group_id' => $update->data['group_id'],
                'name' => $update->data['name'],
                'description' => $update->data['description'],
                'capacity' => $update->data['capacity'],
                'graphic_id' => $update->data['graphic_id'],
                'icon_id' => $update->data['icon_id'],
                'market_group_id' => $update->data['market_group_id'],
                'mass' => $update->data['mass'],
                'packaged_volume' => $update->data['packaged_volume'],
                'portion_size' => $update->data['portion_size'],
                'radius' => $update->data['radius'],
                'volume' => $update->data['volume'],
                'published' => $update->data['published'],
                'hash' => $update->hash
            ], ['type_id'], [
                'group_id',
                'name',
                'description',
                'capacity',
                'graphic_id',
                'icon_id',
                'market_group_id',
                'mass',
                'packaged_volume',
                'portion_size',
                'radius',
                'volume',
                'published',
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
