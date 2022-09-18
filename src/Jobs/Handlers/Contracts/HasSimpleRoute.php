<?php

namespace Clanofartisans\EveEsi\Jobs\Handlers\Contracts;

use Clanofartisans\EveEsi\Routes\ESIRoute;

interface HasSimpleRoute
{
    /**
     * Returns a route containing all data for a single resource type.
     *
     * @return ESIRoute
     */
    public function simpleRoute(): ESIRoute;
}
