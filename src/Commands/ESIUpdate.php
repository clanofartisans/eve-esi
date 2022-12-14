<?php

namespace Clanofartisans\EveEsi\Commands;

use Clanofartisans\EveEsi\Jobs\ESIUpdate as ESIUpdateJob;
use Clanofartisans\EveEsi\Jobs\Handlers\Ancestries;
use Clanofartisans\EveEsi\Jobs\Handlers\Categories;
use Clanofartisans\EveEsi\Jobs\Handlers\Constellations;
use Clanofartisans\EveEsi\Jobs\Handlers\Groups;
use Clanofartisans\EveEsi\Jobs\Handlers\MarketGroups;
use Clanofartisans\EveEsi\Jobs\Handlers\Regions;
use Clanofartisans\EveEsi\Jobs\Handlers\Stargates;
use Clanofartisans\EveEsi\Jobs\Handlers\Stations;
use Clanofartisans\EveEsi\Jobs\Handlers\Structures;
use Clanofartisans\EveEsi\Jobs\Handlers\Systems;
use Clanofartisans\EveEsi\Jobs\Handlers\Types;
use Clanofartisans\EveEsi\Jobs\BuildJumpsCache;
use Illuminate\Console\Command;

class ESIUpdate extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'esi:update {table}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Checks for and downloads updates for the specified EVE ESI table';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle(): int
    {
        try {
            match ($this->argument('table')) {
                'ancestries' =>
                    ESIUpdateJob::dispatch(Ancestries::class),
                'categories' =>
                    ESIUpdateJob::dispatch(Categories::class),
                'constellations' =>
                    ESIUpdateJob::dispatch(Constellations::class),
                'groups' =>
                    ESIUpdateJob::dispatch(Groups::class),
                'jumps_cache' =>
                    BuildJumpsCache::dispatch(),
                'market_groups' =>
                    ESIUpdateJob::dispatch(MarketGroups::class),
                'regions' =>
                    ESIUpdateJob::dispatch(Regions::class),
                'stargates' =>
                    ESIUpdateJob::dispatch(Stargates::class),
                'stations' =>
                    ESIUpdateJob::dispatch(Stations::class),
                'structures' =>
                    ESIUpdateJob::dispatch(Structures::class),
                'systems' =>
                    ESIUpdateJob::dispatch(Systems::class),
                'types' =>
                    ESIUpdateJob::dispatch(Types::class)
            };
        } catch (\UnhandledMatchError $e) {
            $this->error('Table not found!');
            return Command::INVALID;
        }

        $this->info('Update queued successfully!');
        return Command::SUCCESS;
    }
}
