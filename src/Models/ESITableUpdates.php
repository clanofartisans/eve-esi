<?php

namespace Clanofartisans\EveEsi\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @method static \Illuminate\Database\Eloquent\Model|$this create(array $attributes = [])
 * @method static \Illuminate\Database\Eloquent\Model|\Illuminate\Database\Eloquent\Collection|static[]|static|null find($id, $columns = ['*'])
 * @method static bool insert(array $values)
 * @method static \Illuminate\Database\Eloquent\Model|static updateOrCreate(array $attributes, array $values = [])
 * @method static \Illuminate\Database\Eloquent\Builder where($column, $operator = null, $value = null, $boolean = 'and')
 * @method static \Illuminate\Database\Query\Builder whereIn($column, $values, $boolean = 'and', $not = false)
 * @method static \Illuminate\Database\Query\Builder whereIntegerInRaw($column, $values, $boolean = 'and', $not = false)
 *
 * @property string $section
 * @property string $data
 * @property int $data_id
 * @property string $hash
 */
class ESITableUpdates extends Model
{
    protected $table = 'esi_table_updates';
    public $timestamps = false;
    protected $guarded = [];
    protected $casts = ['data' => 'json'];
}
