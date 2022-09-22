<?php

namespace Clanofartisans\EveEsi\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Arr;
use Illuminate\Support\Collection;

/**
 * @method static int insertOrIgnore(array $values)
 * @method \Illuminate\Database\Eloquent\Model|static updateOrCreate(array $attributes, array $values = [])
 * @method static int upsert(array $values, $uniqueBy, $update = null)
 * @method static \Illuminate\Database\Eloquent\Builder where($column, $operator = null, $value = null, $boolean = 'and')
 */
abstract class ESIModel extends Model
{
    /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    public $timestamps = false;

    /**
     * The attributes that aren't mass assignable.
     *
     * @var array
     */
    protected $guarded = [];

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
     * @return Builder $this
     */
    abstract public function whereSection(string $section): Builder;
}
