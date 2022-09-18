<?php

namespace Clanofartisans\EveEsi\Jobs;

class ESIPostProcessing extends ESIJob
{
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
    public function __construct(string $handler, string $section)
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

        $handler->postProcessing($this->section);
    }
}
