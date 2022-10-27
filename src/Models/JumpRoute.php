<?php

namespace Clanofartisans\EveEsi\Models;

use Illuminate\Database\Eloquent\Model;

class JumpRoute extends Model
{
    /**
     * The attributes that aren't mass assignable.
     *
     * @var array<string>|bool
     */
    protected $guarded = [];

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'esi_jumps_cache';

    /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    public $timestamps = false;
}
