<?php

namespace Clanofartisans\EveEsi\Models;

use Illuminate\Database\Eloquent\Model;

class ESITableUpdates extends Model
{
    protected $table = 'esi_table_updates';
    public $timestamps = false;
    protected $guarded = [];
    protected $casts = ['data' => 'json'];
}
