<?php

namespace Clanofartisans\EveEsi\Jobs;

use Clanofartisans\EveEsi\Models\ESITableUpdates;
use Illuminate\Bus\Batchable;

class ESIUpsertLoader extends ESIJob
{
    use Batchable;

    /**
     * The handler class to use for the job.
     *
     * @var string
     */
    protected string $handler;

    /**
     *
     *
     * @var string
     */
    protected string $section;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(string $handler, string $section = '*')
    {
        $this->handler = $handler;
        $this->section = $section;
    }

    /**
     * New
     *
     * @return void
     */
    public function handle(): void
    {
        $handler = new $this->handler;

        ESITableUpdates::select('id')
            ->where('table', $handler->updateTable)
            ->where('section', $this->section)
            ->chunkById(50, function ($updates) {
                $ids = [];
                foreach($updates as $update) {
                    $ids[] = $update->id;
                }
                $load = new ESIUpsertData($this->handler, $this->section, $ids);
                $this->batch()->add($load);
            });
    }
}
