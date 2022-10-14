<?php

namespace Clanofartisans\EveEsi\Models;

use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;

class Group extends ESIModel
{
    /**
     * The string to use for caching this model.
     *
     * @var string
     */
    protected static string $cacheKey = 'esi_groups';

    /**
     * The primary key for the model.
     *
     * @var string
     */
    protected $primaryKey = 'group_id';

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'esi_groups';

    public static function cBPGroups()
    {
        $key = static::$cacheKey.':cBPGroups';

        if(Cache::has($key)) {
            return Cache::get($key);
        }

        $bpGroups = self::select('group_id')->where('category_id', 9)->where('published', 1)->pluck('group_id')->toArray();

        Cache::forever($key, $bpGroups);

        return $bpGroups;
    }

    public static function cRelicGroups()
    {
        $key = static::$cacheKey.':cRelicGroups';

        if(Cache::has($key)) {
            return Cache::get($key);
        }

        $relicGroups = self::select('group_id')->where('category_id', 34)->where('published', 1)->pluck('group_id')->toArray();

        Cache::forever($key, $relicGroups);

        return $relicGroups;
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
            $this->upsert([
                'group_id' => $update->data_id,
                'category_id' => $update->data['category_id'],
                'name' => $update->data['name'],
                'published' => $update->data['published'],
                'hash' => $update->hash
            ], ['group_id'], [
                'category_id',
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
