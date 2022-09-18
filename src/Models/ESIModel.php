<?php

namespace Clanofartisans\EveEsi\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Arr;

/**
 * @method static int insertOrIgnore(array $values)
 * @method \Illuminate\Database\Eloquent\Model|static updateOrCreate(array $attributes, array $values = [])
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
     * Creates a record, or updates an existing record, from JSON data.
     *
     * @param int $id
     * @param array $data
     * @param string $hash
     * @return ESIModel
     */
    abstract public function createFromJson(int $id, array $data, string $hash): ESIModel;
}
