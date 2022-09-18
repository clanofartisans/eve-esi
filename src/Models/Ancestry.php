<?php

namespace Clanofartisans\EveEsi\Models;

class Ancestry extends ESIModel
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'esi_ancestries';

    /**
     * The primary key for the model.
     *
     * @var string
     */
    protected $primaryKey = 'ancestry_id';

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
        $data = $this->clean($data, ['short_description', 'icon_id']);

        return $this->updateOrCreate([
            'ancestry_id' => $id
        ], [
            'bloodline_id' => $data['bloodline_id'],
            'name' => $data['name'],
            'description' => $data['description'],
            'short_description' => $data['short_description'],
            'icon_id' => $data['icon_id'],
            'hash' => $hash
        ]);
    }
}
