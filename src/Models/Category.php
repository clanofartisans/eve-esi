<?php

namespace Clanofartisans\EveEsi\Models;

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
     * @param int $id
     * @param array $data
     * @param string $hash
     * @return ESIModel
     */
    public function createFromJson(int $id, array $data, string $hash): ESIModel
    {
        return $this->updateOrCreate([
            'category_id' => $id
        ], [
            'name' => $data['name'],
            'published' => $data['published'],
            'hash' => $hash
        ]);
    }
}
