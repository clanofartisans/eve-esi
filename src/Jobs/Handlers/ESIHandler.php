<?php

namespace Clanofartisans\EveEsi\Jobs\Handlers;

use Clanofartisans\EveEsi\Auth\RefreshTokenException;
use Clanofartisans\EveEsi\Jobs\ESIBatchFetchData;
use Clanofartisans\EveEsi\Jobs\ESIBatchUpsertData;
use Clanofartisans\EveEsi\Jobs\ESIPruneData;
use Clanofartisans\EveEsi\Jobs\ESISpecialData;
use Clanofartisans\EveEsi\Jobs\ESIUpsertLoader;
use Clanofartisans\EveEsi\Models\ESITableUpdates;
use Clanofartisans\EveEsi\Routes\InvalidESIResponseException;
use Illuminate\Support\Facades\Bus;
use Illuminate\Support\Facades\Cache;
use Throwable;

abstract class ESIHandler
{

    /**
     * The Eloquent model associated with this handler.
     *
     * @var string
     */
    public string $dataModel;

    /**
     * The name of the ID field as retrieved from ESI.
     *
     * @var string
     */
    public string $esiIDName;

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
    public string $updateTable;

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

    /**
     * New
     *
     * @param string $section
     * @return void
     * @throws Throwable
     */
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
     * @param string $section
     * @return void
     */
    public function update(string $section = '*'): void
    {
        $this->section = $section;

//        if(!$this->lock()) {
//            logger('Unable to queue update "'. $this->name() . '" because of a lock.');
//            return;
//        }

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
     * @return array
     */
    protected function buildUpsertBatch(): array
    {
        return [new ESIUpsertLoader($this::class, $this->section)];
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
     * @return bool
     */
    protected function lock(): bool
    {
        $lock = Cache::lock($this->name(), 600);
        return $lock->get();
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
     * @return array
     * @throws InvalidESIResponseException
     * @throws RefreshTokenException
     */
    abstract protected function buildFetchBatch(): array;
}
