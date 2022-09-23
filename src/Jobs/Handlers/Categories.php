<?php

namespace Clanofartisans\EveEsi\Jobs\Handlers;

use Clanofartisans\EveEsi\Facades\EveESI as ESI;
use Clanofartisans\EveEsi\Jobs\Handlers\Concerns\HasIndex;
use Clanofartisans\EveEsi\Models\Category;
use Clanofartisans\EveEsi\Routes\ESIRoute;

class Categories extends ESIHandler
{
    use HasIndex;

    /**
     * The Eloquent model associated with this handler.
     *
     * @var string
     */
    public string $dataModel = Category::class;

    /**
     * The name of the ID field as retrieved from ESI.
     *
     * @var string
     */
    public string $esiIDName = 'category_id';

    /**
     * The internal name of the table associated with this handler.
     *
     * @var string
     */
    public string $updateTable = 'categories';

    /**
     * New - Per Handler
     *
     * @return ESIRoute
     */
    protected function indexRoute(): ESIRoute
    {
        return ESI::universe()->categories();
    }

    /**
     * New - Per Handler
     *
     * @param int $id
     * @return ESIRoute
     */
    protected function resourceRoute(int $id): ESIRoute
    {
        return ESI::universe()->categories()->category($id);
    }
}

