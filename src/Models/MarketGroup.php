<?php

namespace Clanofartisans\EveEsi\Models;

use Illuminate\Support\Collection;

class MarketGroup extends ESIModel
{
    /**
     * The primary key for the model.
     *
     * @var string
     */
    protected $primaryKey = 'market_group_id';

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'esi_market_groups';

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
            $update->data = $this->clean($update->data, ['parent_group_id']);

            $this->upsert([
                'market_group_id' => $update->data_id,
                'parent_group_id' => $update->data['parent_group_id'],
                'name' => $update->data['name'],
                'description' => $update->data['description'],
                'hash' => $update->hash
            ], ['market_group_id'], [
                'parent_group_id',
                'name',
                'description',
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
