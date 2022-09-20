<?php

namespace Clanofartisans\EveEsi\Jobs;

use Clanofartisans\EveEsi\Jobs\Handlers\MarketOrders;
use Illuminate\Queue\Middleware\WithoutOverlapping;
use Throwable;

class ESIQueueOrderUpdates extends ESIJob
{
    /**
     * The region to queue order updates for.
     *
     * @var int
     */
    protected int $region;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(int $region)
    {
        $this->region = $region;
    }

    /**
     * Fetches and processes updates to ESI resource data.
     *
     * @return void
     * @throws Throwable
     */
    public function handle(): void
    {
        $handler = new MarketOrders;

        $handler->queueOrderUpdates($this->region);
    }

    public function middleware()
    {
        return [(new WithoutOverlapping($this->region))->dontRelease()];
    }
}
