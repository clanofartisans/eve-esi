<?php

namespace Clanofartisans\EveEsi\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @method static \Clanofartisans\EveEsi\Routes\Characters characters()
 * @method static \Clanofartisans\EveEsi\Routes\Markets markets()
 * @method static \Clanofartisans\EveEsi\Routes\Universe universe()
 */
class EveESI extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor(): string
    {
        return 'eve-esi';
    }
}
