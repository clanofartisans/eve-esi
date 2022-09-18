<?php

namespace Clanofartisans\EveEsi\Jobs;

use Clanofartisans\EveEsi\Models\ESITableUpdates;
use Illuminate\Bus\Batchable;
use Throwable;

class ESIUpsertLoader extends ESIJob
{
    use Batchable;

    /**
     * The number of seconds the job can run before timing out.
     *
     * @var int
     */
    public int $timeout = 3600;

    /**
     * The number of times the job may be attempted.
     *
     * @var int
     */
    public int $tries = 1;

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
     *
     *
     * @return void
     */
    public function handle(): void
    {
        $handler = new $this->handler;

        ESITableUpdates::where('table', $handler->updateTable)
            ->where('section', $this->section)
            ->chunk(500, function ($updates) {
                $load = [];
                foreach($updates as $update) {
                    $load[] = new ESIUpsertNewResource($this->handler, $update->id);
                }
                $this->batch()->add($load);
            });
    }
}
