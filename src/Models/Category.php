<?php

namespace Clanofartisans\EveEsi\Models;

use Illuminate\Support\Collection;

class Category extends ESIModel
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'esi_categories';

    /**
     * The primary key for the model.
     *
     * @var string
     */
    protected $primaryKey = 'category_id';

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
                'category_id' => $update->data_id,
                'name' => $update->data['name'],
                'published' => $update->data['published'],
                'hash' => $update->hash
            ], ['category_id'], [
                'name',
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
