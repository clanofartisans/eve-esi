<?php

namespace Clanofartisans\EveEsi\Models;

use Illuminate\Support\Collection;

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
     * @param string $section
     * @param Collection $updates
     * @return void
     */
    public function createFromJson(string $section, Collection $updates): void
    {
        foreach($updates as $update) {
            $update->data = $this->clean($update->data, ['security_class', 'star_id']);

            $this->upsert([
                'system_id' => $update->data_id,
                'constellation_id' => $update->data['constellation_id'],
                'name' => $update->data['name'],
                'position_x' => $update->data['position']['x'],
                'position_y' => $update->data['position']['y'],
                'position_z' => $update->data['position']['z'],
                'security_class' => $update->data['security_class'],
                'security_status' => $update->data['security_status'],
                'star_id' => $update->data['star_id'],
                'hash' => $update->hash
            ], ['system_id'], [
                'constellation_id',
                'name',
                'position_x',
                'position_y',
                'position_z',
                'security_class',
                'security_status',
                'star_id',
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
