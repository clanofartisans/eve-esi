<?php

namespace Clanofartisans\EveEsi\Commands;

use Clanofartisans\EveEsi\Jobs\ESIUpdate;
use Clanofartisans\EveEsi\Jobs\Handlers\MarketOrders;
use Illuminate\Console\Command;

class ESIOrders extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'esi:orders {region}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Queues market order updates for the given region';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle(): int
    {
        ESIUpdate::dispatch(MarketOrders::class, $this->argument('region'));

        $this->info('Update queued successfully!');

        return Command::SUCCESS;
    }
}
