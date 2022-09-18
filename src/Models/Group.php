<?php

namespace Clanofartisans\EveEsi\Models;


class Group extends ESIModel
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'esi_groups';

    /**
     * The primary key for the model.
     *
     * @var string
     */
    protected $primaryKey = 'group_id';

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
            'group_id' => $id
        ], [
            'category_id' => $data['category_id'],
            'name' => $data['name'],
            'published' => $data['published'],
            'hash' => $hash
        ]);
    }
}
