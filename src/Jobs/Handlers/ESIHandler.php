<?php

namespace Clanofartisans\EveEsi\Jobs\Handlers;

use Clanofartisans\EveEsi\Auth\RefreshTokenException;
use Clanofartisans\EveEsi\Jobs\ESIFetchResource;
use Clanofartisans\EveEsi\Jobs\ESIPostProcessing;
use Clanofartisans\EveEsi\Jobs\ESIProcessCurrents;
use Clanofartisans\EveEsi\Jobs\ESIProcessDeletes;
use Clanofartisans\EveEsi\Jobs\ESIProcessRawData;
use Clanofartisans\EveEsi\Jobs\ESIProcessUpserts;
use Clanofartisans\EveEsi\Jobs\ESIUpsertLoader;
use Clanofartisans\EveEsi\Models\ESITableUpdates;
use Illuminate\Support\Facades\Bus;
use Throwable;

abstract class ESIHandler
{
    /**
     * The internal name of the table associated with this handler.
     *
     * @var string
     */
    public string $updateTable;

    /**
     * The Eloquent model associated with this handler.
     *
     * @var string
     */
    public string $dataModel;

    /**
     * Deletes the handler's data from Table Updates.
     *
     * @param string $section
     * @return void
     */
    public function cleanupTableUpdates(string $section = '*'): void
    {
        ESITableUpdates::where('table', $this->updateTable)
            ->where('section', $section)
            ->delete();
    }

    /**
     * Removes all deleted records from the handler's table.
     *
     * @param string $section
     * @return void
     */
    public function deleteOldResources(string $section = '*'): void
    {
        $model = new $this->dataModel;
        $table = $model->getTable();
        $key = $model->getKeyName();

        $model->join('esi_table_updates', $table.'.'.$key, '=', 'esi_table_updates.data_id', 'left outer')->whereNull('esi_table_updates.data_id')
            ->delete();
    }

    /**
     * Fetches a resource from ESI and persists it in Table Updates.
     *
     * @param int $id
     * @param string $section
     * @return void
     */
    public function fetchResource(int $id, string $section = '*'): void
    {
        if($response = $this->resourceRoute($id)->get()) {
            $hash = md5($response->body());
            $data = $response->json();
        } else {
            $model = new $this->dataModel;
            $hash = $model->findOrFail($id)->hash;
            $data = '""';
        }

        ESITableUpdates::updateOrCreate([
            'table' => $this->updateTable,
            'section' => $section,
            'data_id' => $id
        ], [
            'hash' => $hash,
            'data' => $data
        ]);
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
     * Handles cleanup and any special post-processing after the data has been upserted.
     *
     * @param string $section
     * @return void
     */
    public function postProcessing(string $section = '*'): void
    {
        $this->cleanupTableUpdates($section);
    }

    /**
     * Processes all the data for the handler from Table Updates.
     *
     * @param string $section
     * @return void
     */
    public function processRawData(string $section = '*'): void
    {
        $handler = $this::class;

        Bus::chain([
            new ESIProcessDeletes($handler, $section),
            new ESIProcessCurrents($handler, $section),
            new ESIProcessUpserts($handler, $section)
        ])->dispatch();
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
        $this->cleanupTableUpdates();

        if($ids = $this->fetchIDs()) {
            $resources = [];
            foreach ($ids as $id) {
                $resources[] = new ESIFetchResource($this::class, $id);
            }

            $handler = $this::class;

            Bus::batch($resources)
                ->then(function () use ($handler) {
                    ESIProcessRawData::dispatch($handler);
                })->dispatch();
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

        $model = new $this->dataModel;

        $model->createFromJson($update->data_id, $update->data, $update->hash);

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
            })->dispatch();
    }
}
