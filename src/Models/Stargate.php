<?php

namespace Clanofartisans\EveEsi\Models;

use Illuminate\Support\Collection;

class Stargate extends ESIModel
{
    /**
     * The primary key for the model.
     *
     * @var string
     */
    protected $primaryKey = 'stargate_id';

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'esi_stargates';

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
                'stargate_id' => $update->data_id,
                'origin_id' => $update->data['system_id'],
                'destination_id' => $update->data['destination']['system_id'],
                'hash' => $update->hash
            ], ['stargate_id'], [
                'origin_id',
                'destination_id',
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
