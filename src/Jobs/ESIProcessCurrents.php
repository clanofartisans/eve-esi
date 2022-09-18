<?php

namespace Clanofartisans\EveEsi\Jobs;

class ESIProcessCurrents extends ESIJob
{
    /**
     * The handler class to use for the job.
     *
     * @var string
     */
    protected string $handler;

    /**
     * The Table Updates section that will be processed.
     *
     * @var string
     */
    protected string $section;

    /**
     * The number of times the job may be attempted.
     *
     * @var int
     */
    public int $tries = 1;

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

        $handler->ignoreCurrentResources($this->section);
    }
}
