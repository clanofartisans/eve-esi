<?php

namespace Clanofartisans\EveEsi\Jobs;

use Illuminate\Bus\Batchable;

class ESIUpsertNewResource extends ESIJob
{
    use Batchable;

    /**
     * The handler class to use for the job.
     *
     * @var string
     */
    protected string $handler;

    /**
     * The Table Updates ID for the resource we're upserting.
     *
     * @var int
     */
    protected int $id;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(string $handler, int $id)
    {
        $this->handler = $handler;
        $this->id = $id;
    }

    /**
     * Upsert the data from Table Updates to the handler's table.
     *
     * @return void
     */
    public function handle(): void
    {
        if($this->batch()->cancelled()) {
            return;
        }

        $handler = new $this->handler;

        $handler->upsertNewResource($this->id);
    }
}
