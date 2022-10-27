<?php

namespace Clanofartisans\EveEsi\Jobs\Handlers;

use Clanofartisans\EveEsi\Facades\EveESI as ESI;
use Clanofartisans\EveEsi\Jobs\Handlers\Concerns\HasIndex;
use Clanofartisans\EveEsi\Models\Stargate;
use Clanofartisans\EveEsi\Models\Station;
use Clanofartisans\EveEsi\Models\System;
use Clanofartisans\EveEsi\Routes\ESIRoute;
use Illuminate\Support\Collection;

class Stargates extends ESIHandler
{
    use HasIndex;

    /**
     * The Eloquent model associated with this handler.
     *
     * @var string
     */
    public string $dataModel = Stargate::class;

    /**
     * The name of the ID field as retrieved from ESI.
     *
     * @var string
     */
    public string $esiIDName = 'stargate_id';

    /**
     * The internal name of the table associated with this handler.
     *
     * @var string
     */
    public string $updateTable = 'stargates';

    /**
     * New
     *
     * @param string $section
     * @return void
     */
    public function specialData(string $section): void
    {
        $this->specialCalculateSecurity();

        parent::specialData($section);
    }

    /**
     * New - Specific override for this handler
     *
     * @return Collection
     */
    protected function fetchIndex(): Collection
    {
        return Stargate::all('stargate_id')->pluck('stargate_id');
    }

    /**
     * New - Per Handler, note that this isn't actually a valid route
     *
     * @return ESIRoute
     */
    protected function indexRoute(): ESIRoute
    {
        return ESI::universe()->stargates();
    }

    /**
     * New - Per Handler
     *
     * @param int $id
     * @return ESIRoute
     */
    protected function resourceRoute(int $id): ESIRoute
    {
        return ESI::universe()->stargates()->stargate($id);
    }

    /**
     * New
     *
     * @return void
     */
    protected function specialCalculateSecurity(): void
    {
        $destinations = Stargate::select('destination_id')
            ->distinct()
            ->pluck('destination_id');

        $systems = System::select(['system_id', 'security_status'])
            ->whereIntegerInRaw('system_id', $destinations)
            ->get();

        foreach($systems as $system) {
            $secure = !($system->security_status < 0.45);
            Stargate::where('destination_id', $system->system_id)
                ->update(['secure' => $secure]);
        }
    }
}
