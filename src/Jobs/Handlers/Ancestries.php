<?php

namespace Clanofartisans\EveEsi\Jobs\Handlers;

use Clanofartisans\EveEsi\Auth\RefreshTokenException;
use Clanofartisans\EveEsi\Facades\EveESI as ESI;
use Clanofartisans\EveEsi\Jobs\ESIProcessRawData;
use Clanofartisans\EveEsi\Jobs\Handlers\Contracts\HasSimpleRoute;
use Clanofartisans\EveEsi\Models\Ancestry;
use Clanofartisans\EveEsi\Models\ESITableUpdates;
use Clanofartisans\EveEsi\Routes\ESIRoute;
use Throwable;

class Ancestries extends ESIHandler implements HasSimpleRoute
{
    /**
     * The internal name of the table associated with this handler.
     *
     * @var string
     */
    public string $updateTable = 'ancestries';

    /**
     * The Eloquent model associated with this handler.
     *
     * @var string
     */
    public string $dataModel = Ancestry::class;

    /**
     *
     *
     * @return ESIRoute
     */
    public function simpleRoute(): ESIRoute
    {
        return ESI::universe()->ancestries();
    }

    /**
     * Fetches all data for the handler and persists it to Table Updates, then kicks off processing.
     *
     * @return void
     * @throws RefreshTokenException
     * @throws Throwable
     */
    public function updateData(): void
    {
        $section = '*';

        $this->cleanupTableUpdates();

        if($response = $this->simpleRoute()->get()) {
            $all = $response->json();

            foreach($all as $item) {
                $hash = md5(json_encode($item));

                ESITableUpdates::create([
                    'table' => $this->updateTable,
                    'section' => $section,
                    'data_id' => $item['id'],
                    'hash' => $hash,
                    'data' => $item
                ]);
            }

            $handler = $this::class;

            ESIProcessRawData::dispatch($handler);
        }
    }
}
