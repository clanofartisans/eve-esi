<?php

namespace Clanofartisans\EveEsi\Jobs\Handlers;

use Clanofartisans\EveEsi\Auth\RefreshTokenException;
use Clanofartisans\EveEsi\Facades\EveESI as ESI;
use Clanofartisans\EveEsi\Jobs\ESIPostProcessing;
use Clanofartisans\EveEsi\Jobs\ESIProcessRawData;
use Clanofartisans\EveEsi\Jobs\ESIUpdateOrders;
use Clanofartisans\EveEsi\Jobs\ESIUpsertLoader;
use Clanofartisans\EveEsi\Models\ESITableUpdates;
use Clanofartisans\EveEsi\Models\MarketOrder;
use Clanofartisans\EveEsi\Routes\InvalidESIResponseException;
use Illuminate\Support\Facades\Bus;
use Throwable;

class MarketOrders extends ESIHandler
{
    /**
     * The internal name of the table associated with this handler.
     *
     * @var string
     */
    public string $updateTable = 'market_orders';

    /**
     * The Eloquent model associated with this handler.
     *
     * @var string
     */
    public string $dataModel = MarketOrder::class;

    /**
     *
     *
     * @param string $section
     * @return void
     */
    public function deleteOldResources(string $section = '*'): void
    {
        $model = new $this->dataModel;
        $table = $model->getTable();
        $key = $model->getKeyName();

        $model->where('region_id', $section)
            ->join('esi_table_updates', $table.'.'.$key, '=', 'esi_table_updates.data_id', 'left outer')
            ->whereNull('esi_table_updates.data_id')
            ->delete();
    }

    /**
     * Removes all up-to-date records for the handler from Table Updates.
     *
     * @param string $section
     * @return void
     */
    public function ignoreCurrentResources(string $section = '*'): void
    {
        $model = new $this->dataModel;
        $table = $model->getTable();

        ESITableUpdates::join($table, $table.'.hash', '=', 'esi_table_updates.hash', 'left outer')
            ->where('esi_table_updates.table', $this->updateTable)
            ->where('esi_table_updates.section', $section)
            ->whereNotNull($table.'.hash')
            ->delete();
    }

    /**
     *
     *
     * @param int $region
     * @return void
     * @throws Throwable
     */
    public function queueOrderUpdates(int $region): void
    {
        $this->cleanupTableUpdates($region);

        $pages = ESI::markets()->region($region)->orders()->getNumPages();

        $batch = [];
        for($i = 1; $i <= $pages; $i++) {
            $batch[] = new ESIUpdateOrders($region, $i);
        }

        $handler = $this::class;
        $section = (string) $region;

        Bus::batch($batch)
            ->then(function () use ($handler, $section) {
                ESIProcessRawData::dispatch($handler, $section);
            })->dispatch();
    }

    /**
     *
     *
     * @param int $region
     * @param int $page
     * @return void
     * @throws RefreshTokenException
     * @throws InvalidESIResponseException
     */
    public function updateOrders(int $region, int $page): void
    {
        $orders = ESI::markets()->region($region)->orders()->page($page)->get()->json();

        foreach($orders as $order) {
            $hash = md5(json_encode($order));

            ESITableUpdates::create([
                'table' => $this->updateTable,
                'section' => $region,
                'data_id' => $order['order_id'],
                'hash' => $hash,
                'data' => $order
            ]);
        }
    }

    /**
     * Upserts a single resource for the handler from Table Updates.
     *
     * @param int $id
     * @return void
     */
    public function upsertNewResource(int $id): void
    {
        $update = ESITableUpdates::find($id);

        $data = $update->data;
        $data['region_id'] = $update->section;

        $model = new $this->dataModel;

        $model->createFromJson($update->data_id, $data, $update->hash);

        $update->delete();
    }

    /**
     * Processes the upserts for all data for the handler from Table Updates.
     *
     * @param string $section
     * @return void
     * @throws Throwable
     */
    public function upsertNewResources(string $section = '*'): void
    {
        $loader = new ESIUpsertLoader($this::class, $section);

        $handler = $this::class;

        Bus::batch([$loader])
            ->then(function () use ($handler, $section) {
                ESIPostProcessing::dispatch($handler, $section);
            })
            ->allowFailures() // The ESIUpsertLoader can fail and re-run inexplicably on huge datasets. This is a "temporary" fix.
            ->dispatch();
    }
}
