<?php

namespace Clanofartisans\EveEsi\Models;

use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;

class Type extends ESIModel
{
    /**
     * The string to use for caching this model.
     *
     * @var string
     */
    protected static string $cacheKey = 'esi_types';

    /**
     * The primary key for the model.
     *
     * @var string
     */
    protected $primaryKey = 'type_id';

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'esi_types';

    public function cBreadcrumbs()
    {
        $key = static::$cacheKey.':cBreadcrumbs:'.$this->type_id;

        if(Cache::has($key)) {
            return Cache::get($key);
        }

        $i = 0;

        $breadcrumbs[$i] = MarketGroup::select('market_group_id', 'parent_group_id', 'name')->findOrFail($this->market_group_id)->toArray();

        while(!empty($breadcrumbs[$i]['parent_group_id'])) {
            $breadcrumbs[$i+1] = MarketGroup::select('market_group_id', 'parent_group_id', 'name')->findOrFail($breadcrumbs[$i]['parent_group_id'])->toArray();
            $i++;
        }

        $breadcrumbs = array_reverse($breadcrumbs, true);

        Cache::forever($key, $breadcrumbs);

        return $breadcrumbs;
    }

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
            $update->data = $this->clean($update->data, [
                'capacity',
                'graphic_id',
                'icon_id',
                'market_group_id',
                'mass',
                'packaged_volume',
                'portion_size',
                'radius',
                'volume'
            ]);

            $this->upsert([
                'type_id' => $update->data_id,
                'group_id' => $update->data['group_id'],
                'name' => $update->data['name'],
                'description' => $update->data['description'],
                'capacity' => $update->data['capacity'],
                'graphic_id' => $update->data['graphic_id'],
                'icon_id' => $update->data['icon_id'],
                'market_group_id' => $update->data['market_group_id'],
                'mass' => $update->data['mass'],
                'packaged_volume' => $update->data['packaged_volume'],
                'portion_size' => $update->data['portion_size'],
                'radius' => $update->data['radius'],
                'volume' => $update->data['volume'],
                'published' => $update->data['published'],
                'hash' => $update->hash
            ], ['type_id'], [
                'group_id',
                'name',
                'description',
                'capacity',
                'graphic_id',
                'icon_id',
                'market_group_id',
                'mass',
                'packaged_volume',
                'portion_size',
                'radius',
                'volume',
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
