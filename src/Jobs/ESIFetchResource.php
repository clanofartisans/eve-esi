<?php

namespace Clanofartisans\EveEsi\Jobs;

use Illuminate\Bus\Batchable;

class ESIFetchResource extends ESIJob
{
    use Batchable;

    /**
     * The handler class to use for the job.
     *
     * @var string
     */
    protected string $handler;

    /**
     * The ID for the resource we're fetching.
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
     * Fetch the details for the resource and adds them to the ESI Table Updates table.
     *
     * @return void
     */
    public function handle(): void
    {
        if($this->batch()->cancelled()) {
            return;
        }

        $handler = new $this->handler;

        $handler->fetchResource($this->id);
    }
}
