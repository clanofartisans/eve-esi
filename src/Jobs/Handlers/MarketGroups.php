<?php

namespace Clanofartisans\EveEsi\Jobs\Handlers;

use Clanofartisans\EveEsi\Facades\EveESI as ESI;
use Clanofartisans\EveEsi\Jobs\Handlers\Concerns\HasIndex;
use Clanofartisans\EveEsi\Models\ESITableUpdates;
use Clanofartisans\EveEsi\Models\MarketGroup;
use Clanofartisans\EveEsi\Models\Station;
use Clanofartisans\EveEsi\Routes\ESIRoute;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;

class MarketGroups extends ESIHandler
{
    use HasIndex;

    /**
     * The Eloquent model associated with this handler.
     *
     * @var string
     */
    public string $dataModel = MarketGroup::class;

    /**
     * The name of the ID field as retrieved from ESI.
     *
     * @var string
     */
    public string $esiIDName = 'market_group_id';

    /**
     * The internal name of the table associated with this handler.
     *
     * @var string
     */
    public string $updateTable = 'market_groups';

    /**
     * New
     *
     * @param string $section
     * @return void
     */
    public function specialData(string $section): void
    {
        $this->specialSetMarketGroupTypes();
        $this->clearTableUpdates();

        parent::specialData($section);
    }

    /**
     * New - Note override to delay clearing table updates
     *
     * @param string $section
     * @param array $ids
     * @return void
     */
    public function upsertData(string $section, array $ids): void
    {
        $updates = ESITableUpdates::whereIntegerInRaw('id', $ids)
            ->get(['data_id', 'data', 'hash']);

        $model = new $this->dataModel;

        $model->createFromJson($section, $updates);
    }

    /**
     * New - Per Handler
     *
     * @return ESIRoute
     */
    protected function indexRoute(): ESIRoute
    {
        return ESI::markets()->groups();
    }

    /**
     * New - Per Handler
     *
     * @param int $id
     * @return ESIRoute
     */
    protected function resourceRoute(int $id): ESIRoute
    {
        return ESI::markets()->groups()->market_group($id);
    }

    /**
     * New
     *
     * @return void
     */
    protected function specialSetMarketGroupTypes(): void
    {
        DB::table('esi_market_group_types')->truncate();
        ESITableUpdates::where('table', $this->updateTable)
            ->where('section', $this->section)
            ->lazyById()->each(function ($group) {
                $types = [];
                foreach($group->data['types'] as $type) {
                    $types[] = [
                        'market_group_id' => $group->data_id,
                        'type_id' => $type
                    ];
                }
                DB::table('esi_market_group_types')
                    ->insertOrIgnore($types);
            });
    }
}
