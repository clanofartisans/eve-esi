<?php

namespace Clanofartisans\EveEsi\Models;

use Illuminate\Support\Collection;

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
     * @param string $section
     * @param Collection $updates
     * @return void
     */
    public function createFromJson(string $section, Collection $updates): void
    {
        foreach($updates as $update) {
            $this->upsert([
                'constellation_id' => $update->data_id,
                'region_id' => $update->data['region_id'],
                'name' => $update->data['name'],
                'position_x' => $update->data['position']['x'],
                'position_y' => $update->data['position']['y'],
                'position_z' => $update->data['position']['z'],
                'hash' => $update->hash
            ], ['constellation_id'], ['hash']);
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
