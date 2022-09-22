<?php

namespace Clanofartisans\EveEsi\Jobs\Handlers;

use Clanofartisans\EveEsi\Auth\RefreshTokenException;
use Clanofartisans\EveEsi\Facades\EveESI as ESI;
use Clanofartisans\EveEsi\Jobs\ESIBatchFetchData;
use Clanofartisans\EveEsi\Jobs\ESIBatchUpsertData;
use Clanofartisans\EveEsi\Jobs\ESIFetchData;
use Clanofartisans\EveEsi\Jobs\ESIPruneData;
use Clanofartisans\EveEsi\Jobs\ESISpecialData;
use Clanofartisans\EveEsi\Jobs\ESIUpsertLoader;
use Clanofartisans\EveEsi\Models\ESITableUpdates;
use Clanofartisans\EveEsi\Models\MarketOrder;
use Clanofartisans\EveEsi\Routes\ESIRoute;
use Clanofartisans\EveEsi\Routes\InvalidESIResponseException;
use Illuminate\Support\Facades\Bus;
use Illuminate\Support\Facades\Cache;
use Throwable;

class MarketOrders extends ESIHandler
{

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
     * The logical section used for the job data.
     *
     * @var string
     */
    public string $section = '*';

    /**
     * The internal name of the table associated with this handler.
     *
     * @var string
     */
    public string $updateTable = 'market_orders';

    /**
     * New
     *
     * @param string $section
     * @return void
     */
    public function update(string $section = '*'): void
    {
        $this->section = $section;

        if(!$this->lock()) {
            logger('Unable to queue update "'. $this->name() . '" because of a lock.');
            return;
        }

        $this->clearTableUpdates();

        ESIBatchFetchData::dispatch($this::class, $this->section);
        // Then ESIPruneData
        // Then ESIBatchUpsertData
        // Then ESISpecialData
    }

    /**
     * New
     *
     * @param string $section
     * @return void
     */
    public function specialData(string $section): void
    {
        $this->section = $section;

        Cache::lock($this->name())->forceRelease();
    }

    /**
     * New
     *
     * @return array
     */
    protected function buildUpsertBatch(): array
    {
        return [new ESIUpsertLoader($this::class, $this->section)];
    }

    /**
     * New
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

        ESITableUpdates::whereIntegerInRaw('id', $ids)
            ->delete();
    }

    /**
     * New
     *
     * @param string $section
     * @return void
     */
    public function pruneData(string $section): void
    {
        $this->section = $section;

        $this->deleteOldData();
        $this->ignoreCurrentData();

        ESIBatchUpsertData::dispatch($this::class, $this->section);
    }

    /**
     * New
     *
     * @return void
     */
    protected function deleteOldData(): void
    {
        $model = new $this->dataModel;
        $table = $model->getTable();
        $key = $model->getKeyName();

        $model->whereSection($this->section)
            ->join('esi_table_updates', $table.'.'.$key, '=', 'esi_table_updates.data_id', 'left outer')
            ->whereNull('esi_table_updates.data_id')
            ->delete();
    }

    /**
     * New
     *
     * @return void
     */
    protected function ignoreCurrentData(): void
    {
        $model = new $this->dataModel;
        $table = $model->getTable();

        ESITableUpdates::join($table, $table.'.hash', '=', 'esi_table_updates.hash', 'left outer')
            ->where('esi_table_updates.table', $this->updateTable)
            ->where('esi_table_updates.section', $this->section)
            ->whereNotNull($table.'.hash')
            ->delete();
    }

    /**
     * New
     *
     * @param string $section
     * @param int $page
     * @return void
     * @throws InvalidESIResponseException
     * @throws RefreshTokenException
     */
    public function fetchData(string $section, int $page): void
    {
        $this->section = $section;

        $data = $this->baseRoute()->page($page)->get()->collect();

        foreach($data->chunk(50) as $chunk) {
            $updates = [];
            foreach($chunk as $datum) {
                $hash = md5(json_encode($datum));

                $updates[] = [
                    'table' => $this->updateTable,
                    'section' => $this->section,
                    'data_id' => $datum[$this->esiIDName],
                    'hash' => $hash,
                    'data' => json_encode($datum)
                ];
            }
            ESITableUpdates::insert($updates);
        }
    }

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
        Bus::batch($batch)->then(function () {
            ESIPruneData::dispatch($this::class, $this->section);
        })->dispatch();
    }

    public function batchUpsertData(string $section): void
    {
        $this->section = $section;

        $batch = $this->buildUpsertBatch();
        Bus::batch($batch)->then(function () {
            ESISpecialData::dispatch($this::class, $this->section);
        })->dispatch();
    }

    /**
     * New
     *
     * @return array
     */
    protected function buildFetchBatch(): array
    {
        $pages = $this->pages();
        $batch = [];
        for($i = 1; $i <= $pages; $i++) {
            $batch[] = new ESIFetchData($this::class, $this->section, $i);
        }

        return $batch;
    }

    /**
     * New
     *
     * @return int
     */
    protected function pages(): int
    {
        return $this->baseRoute()->getNumPages();
    }

    /**
     * New - Per Handler
     *
     * @return ESIRoute
     */
    protected function baseRoute(): ESIRoute
    {
        return ESI::markets()->region($this->section)->orders();
    }

    /**
     * New
     *
     * @return string
     */
    protected function name(): string
    {
        return $this->updateTable . ':' . $this->section;
    }

    /**
     * New
     *
     * @return void
     */
    protected function clearTableUpdates(): void
    {
        ESITableUpdates::where('table', $this->updateTable)
            ->where('section', $this->section)
            ->delete();
    }

    /**
     * New
     *
     * @return bool
     */
    protected function lock(): bool
    {
        $lock = Cache::lock($this->name(), 600);
        return $lock->get();
    }
}
