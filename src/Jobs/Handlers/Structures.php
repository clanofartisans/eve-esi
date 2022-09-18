<?php

namespace Clanofartisans\EveEsi\Jobs\Handlers;

use Clanofartisans\EveEsi\Auth\RefreshTokenException;
use Clanofartisans\EveEsi\Facades\EveESI as ESI;
use Clanofartisans\EveEsi\Jobs\Handlers\Contracts\HasResourceListRoute;
use Clanofartisans\EveEsi\Jobs\Handlers\Contracts\HasSingleResourceRoute;
use Clanofartisans\EveEsi\Models\MarketOrder;
use Clanofartisans\EveEsi\Models\Structure;
use Clanofartisans\EveEsi\Routes\ESIRoute;
use Clanofartisans\EveEsi\Routes\InvalidESIResponseException;
use Illuminate\Support\Collection;

class Structures extends ESIHandler implements HasResourceListRoute, HasSingleResourceRoute
{
    /**
     * The internal name of the table associated with this handler.
     *
     * @var string
     */
    public string $updateTable = 'structures';

    /**
     * The Eloquent model associated with this handler.
     *
     * @var string
     */
    public string $dataModel = Structure::class;

    /**
     * Retrieves and returns a list of record IDs from the ESI API.
     *
     * @return array
     * @throws InvalidESIResponseException
     * @throws RefreshTokenException
     */
    public function fetchIDs(): array
    {
        $public = ESI::universe()->structures()->get()->json();

        $markets = MarketOrder::select('location_id')
            ->where('location_type', 'structure')
            ->distinct()
            ->pluck('location_id');

        return $markets->merge($public)->unique()->toArray();
    }

    /**
     * Returns the route pointing to a single resource for this handler.
     *
     * @param int $id
     * @return ESIRoute
     */
    public function resourceRoute(int $id): ESIRoute
    {
        return ESI::universe()->structures()->structure($id)->auth();
    }
}
