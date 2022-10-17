<?php

namespace Clanofartisans\EveEsi\Commands;

use Clanofartisans\EveEsi\Jobs\DenormalizeLocations;
use Clanofartisans\EveEsi\Jobs\GetCharacterLocations;
use Illuminate\Console\Command;

class YamsUpdate extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'yams:update {table}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Runs updates specific to Yams.Market';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle(): int
    {
        try {
            match ($this->argument('table')) {
                'character_locations' =>
                    GetCharacterLocations::dispatch(),
                'locations' =>
                    DenormalizeLocations::dispatch(),
            };
        } catch (\UnhandledMatchError $e) {
            $this->error('Table not found!');
            return Command::INVALID;
        }

        $this->info('Update queued successfully!');
        return Command::SUCCESS;
    }
}
