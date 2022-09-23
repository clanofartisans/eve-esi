<?php

namespace Clanofartisans\EveEsi\Models;

use Illuminate\Support\Collection;

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
     * @param string $section
     * @param Collection $updates
     * @return void
     */
    public function createFromJson(string $section, Collection $updates): void
    {
        foreach ($updates as $update) {
            $this->upsert([
                'structure_id' => $update->data_id,
                'system_id' => $update->data['solar_system_id'],
                'name' => $update->data['name'],
                'owner_id' => $update->data['owner_id'],
                'position_x' => $update->data['position']['x'],
                'position_y' => $update->data['position']['y'],
                'position_z' => $update->data['position']['z'],
                'type_id' => $update->data['type_id'],
                'hash' => $update->hash
            ], ['structure_id'], [
                'system_id',
                'name',
                'owner_id',
                'position_x',
                'position_y',
                'position_z',
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
