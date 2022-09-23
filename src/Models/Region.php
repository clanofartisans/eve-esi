<?php

namespace Clanofartisans\EveEsi\Models;

use Illuminate\Support\Collection;

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
     * @param string $section
     * @param Collection $updates
     * @return void
     */
    public function createFromJson(string $section, Collection $updates): void
    {
        foreach($updates as $update) {
            $update->data = $this->clean($update->data, ['description']);

            $this->upsert([
                'region_id' => $update->data_id,
                'name' => $update->data['name'],
                'description' => $update->data['description'],
                'hash' => $update->hash
            ], ['region_id'], ['name', 'description', 'hash']);
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
