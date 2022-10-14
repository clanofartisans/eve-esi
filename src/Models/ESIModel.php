<?php

namespace Clanofartisans\EveEsi\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Arr;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;

abstract class ESIModel extends Model
{
    /**
     * The string to use for caching this model.
     *
     * @var string
     */
    protected static string $cacheKey = 'model_name';

    /**
     * The attributes that aren't mass assignable.
     *
     * @var array<string>|bool
     */
    protected $guarded = [];

    /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    public $timestamps = false;

    /**
     *
     *
     * @param $id
     * @return ESIModel
     */
    public static function cFind($id): ESIModel
    {
        $key = static::$cacheKey.':cFind:'.$id;

        if(Cache::has($key)) {
            return Cache::get($key);
        }

        $resource = self::findOrFail($id);

        Cache::forever($key, $resource);

        return $resource;
    }

    /**
     * Cleans up empty or non-existent array keys.
     *
     * @param array $data
     * @param array $keys
     * @return array
     */
    protected function clean(array $data, array $keys): array
    {
        foreach($keys as $key) {
            $data[$key] = Arr::exists($data, $key) ? $data[$key] : null;
        }

        return $data;
    }

    /**
     * New
     *
     * @param string $section
     * @param Collection $updates
     * @return void
     */
    abstract public function createFromJson(string $section, Collection $updates): void;

    /**
     * New
     *
     * @param string $section
     * @return $this
     */
    abstract public function whereSection(string $section);
}
