<?php

namespace Clanofartisans\EveEsi\Models;

use Illuminate\Support\Collection;

class Ancestry extends ESIModel
{
    /**
     * The primary key for the model.
     *
     * @var string
     */
    protected $primaryKey = 'ancestry_id';

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'esi_ancestries';

    /**
     * New
     *
     * @param string $section
     * @param Collection $updates
     * @return void
     */
    public function createFromJson(string $section, Collection $updates): void
    {
        foreach($updates as $update) {
            $update->data = $this->clean($update->data, ['short_description', 'icon_id']);

            $this->upsert([
                'ancestry_id' => $update->data_id,
                'bloodline_id' => $update->data['bloodline_id'],
                'name' => $update->data['name'],
                'description' => $update->data['description'],
                'short_description' => $update->data['short_description'],
                'icon_id' => $update->data['icon_id'],
                'hash' => $update->hash
            ], ['ancestry_id'], [
                'bloodline_id',
                'name',
                'description',
                'short_description',
                'icon_id',
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
