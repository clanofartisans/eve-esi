<?php

namespace Clanofartisans\EveEsi\Jobs;

use Illuminate\Bus\Batchable;
use Throwable;

class ESIUpsertData extends ESIJob
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
     * New
     *
     * @var array
     */
    protected array $ids;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(string $handler, string $section, array $ids)
    {
        $this->handler = $handler;
        $this->section = $section;
        $this->ids = $ids;
    }

    /**
     * New
     *
     * @return void
     * @throws Throwable
     */
    public function handle(): void
    {
        $handler = new $this->handler;

        $handler->upsertData($this->section, $this->ids);
    }
}
