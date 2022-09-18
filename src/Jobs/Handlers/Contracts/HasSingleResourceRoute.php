<?php

namespace Clanofartisans\EveEsi\Jobs\Handlers\Contracts;

use Clanofartisans\EveEsi\Routes\ESIRoute;

interface HasSingleResourceRoute
{
    /**
     * Returns the route pointing to a single resource for this handler.
     *
     * @param int $id
     * @return ESIRoute
     */
    public function resourceRoute(int $id): ESIRoute;
}
