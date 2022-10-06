<?php

namespace Clanofartisans\EveEsi\Jobs\Handlers;

use Clanofartisans\EveEsi\Auth\RefreshTokenException;
use Clanofartisans\EveEsi\Facades\EveESI as ESI;
use Clanofartisans\EveEsi\Jobs\ESIPruneData;
use Clanofartisans\EveEsi\Jobs\Handlers\Concerns\HasIndex;
use Clanofartisans\EveEsi\Models\MarketOrder;
use Clanofartisans\EveEsi\Models\Structure;
use Clanofartisans\EveEsi\Models\System;
use Clanofartisans\EveEsi\Routes\ESIRoute;
use Clanofartisans\EveEsi\Routes\InvalidESIResponseException;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Bus;
use Throwable;

class Structures extends ESIHandler
{
    use HasIndex;

    /**
     * The Eloquent model associated with this handler.
     *
     * @var string
     */
    public string $dataModel = Structure::class;

    /**
     * The name of the ID field as retrieved from ESI.
     *
     * @var string
     */
    public string $esiIDName = 'structure_id';

    /**
     * The internal name of the table associated with this handler.
     *
     * @var string
     */
    public string $updateTable = 'structures';

    /**
     * New
     *
     * @param string $section
     * @return void
     * @throws Throwable
     */
    public function batchFetchData(string $section): void
    {
        $this->section = $section;

        $batch = $this->buildFetchBatch();
        Bus::batch($batch)->finally(function () {
            ESIPruneData::dispatch($this::class, $this->section);
        })->allowFailures()->dispatch();
    }

    /**
     * New
     *
     * @param string $section
     * @return void
     */
    public function specialData(string $section): void
    {
        $this->specialSetSecurityStatus();

        parent::specialData($section);
    }

    /**
     * New
     *
     * @return Collection
     * @throws InvalidESIResponseException
     * @throws RefreshTokenException
     */
    protected function fetchIndex(): Collection
    {
        $public = $this->indexRoute()->get()->json();

        $markets = MarketOrder::select('location_id')
            ->where('location_id', '>', 1000000000000)
            ->distinct()
            ->pluck('location_id');

        return $markets->merge($public)->unique();
    }

    /**
     * New - Per Handler
     *
     * @return ESIRoute
     */
    protected function indexRoute(): ESIRoute
    {
        return ESI::universe()->structures();
    }

    /**
     * New - Per Handler
     *
     * @param int $id
     * @return ESIRoute
     */
    protected function resourceRoute(int $id): ESIRoute
    {
        return ESI::universe()->structures()->structure($id)->auth();
    }

    /**
     * New
     *
     * @return void
     */
    protected function specialSetSecurityStatus(): void
    {
        $systems = System::all();

        foreach($systems as $system) {
            Structure::where('system_id', $system->system_id)
                ->update(['security_status' => $system->security_status]);
        }
    }
}
