<?php

namespace Clanofartisans\EveEsi\Jobs\Handlers;

use Clanofartisans\EveEsi\Facades\EveESI as ESI;
use Clanofartisans\EveEsi\Jobs\Handlers\Concerns\NoIndex;
use Clanofartisans\EveEsi\Models\MarketOrder;
use Clanofartisans\EveEsi\Routes\ESIRoute;

class MarketOrders extends ESIHandler
{
    use NoIndex;

    /**
     * The Eloquent model associated with this handler.
     *
     * @var string
     */
    public string $dataModel = MarketOrder::class;

    /**
     * The name of the ID field as retrieved from ESI.
     *
     * @var string
     */
    public string $esiIDName = 'order_id';

    /**
     * The internal name of the table associated with this handler.
     *
     * @var string
     */
    public string $updateTable = 'market_orders';

    /**
     * New - Per Handler
     *
     * @return ESIRoute
     */
    protected function baseRoute(): ESIRoute
    {
        return ESI::markets()->region($this->section)->orders();
    }
}
