<?php

namespace Clanofartisans\EveEsi;

use Clanofartisans\EveEsi\Routes\Characters;
use Clanofartisans\EveEsi\Routes\Markets;
use Clanofartisans\EveEsi\Routes\Universe;

class EveESI
{
    /**
     * Handles "/characters" endpoints.
     *
     * @return Characters
     */
    public static function characters(): Characters
    {
        return new Characters;
    }

    /**
     * Handles "/markets" endpoints.
     *
     * @return Markets
     */
    public static function markets(): Markets
    {
        return new Markets;
    }

    /**
     * Handles "/universe" endpoints.
     *
     * @return Universe
     */
    public static function universe(): Universe
    {
        return new Universe;
    }
}
