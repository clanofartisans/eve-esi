<?php

namespace Clanofartisans\EveEsi\Jobs;

use Clanofartisans\EveEsi\Jobs\Handlers\MarketOrders;
use Illuminate\Bus\Batchable;
use Throwable;

class ESIUpdateOrders extends ESIJob
{
    use Batchable;

    /**
     * The region to queue the order update for.
     *
     * @var int
     */
    protected int $region;

    /**
     *
     *
     * @var int
     */
    protected int $page;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(int $region, int $page)
    {
        $this->region = $region;
        $this->page = $page;
    }

    /**
     * Fetches and processes updates to ESI resource data.
     *
     * @return void
     * @throws Throwable
     */
    public function handle(): void
    {
        if($this->batch()->cancelled()) {
            return;
        }

        $handler = new MarketOrders;

        $handler->updateOrders($this->region, $this->page);
    }
}
