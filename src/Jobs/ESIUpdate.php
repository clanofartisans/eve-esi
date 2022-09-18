<?php

namespace Clanofartisans\EveEsi\Jobs;

use Throwable;

class ESIUpdate extends ESIJob
{
    /**
     * The handler class to use for the job.
     *
     * @var string
     */
    protected string $handler;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(string $handler)
    {
        $this->handler = $handler;
    }

    /**
     * Fetches and processes updates to ESI resource data.
     *
     * @return void
     * @throws Throwable
     */
    public function handle(): void
    {
        $handler = new $this->handler;

        $handler->updateData();
    }
}
