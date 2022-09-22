<?php

namespace Clanofartisans\EveEsi\Jobs;

use Illuminate\Bus\Batchable;

class ESIFetchData extends ESIJob
{
    use Batchable;

    /**
     * The handler class to use for the job.
     *
     * @var string
     */
    protected string $handler;

    /**
     * The logical section used for the job data.
     *
     * @var string
     */
    protected string $section;

    /**
     * The page to be fetched from ESI.
     *
     * @var int
     */
    protected int $page;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(string $handler, string $section = '*', int $page = 1)
    {
        $this->handler = $handler;
        $this->section = $section;
        $this->page = $page;
    }

    /**
     * New
     *
     * @return void
     */
    public function handle(): void
    {
        $handler = new $this->handler;

        $handler->fetchData($this->section, $this->page);
    }
}
