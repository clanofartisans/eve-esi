<?php

namespace Clanofartisans\EveEsi\Jobs;

use Clanofartisans\EveEsi\Models\JumpRoute;
use Clanofartisans\EveEsi\Models\Stargate;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class BuildJumpsCache implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * The number of seconds the job can run before timing out.
     *
     * @var int
     */
    public int $timeout = 300;

    /**
     * New
     *
     * @return void
     */
    public function handle(): void
    {
        JumpRoute::truncate();

        $systems = Stargate::select(['destination_id'])->distinct()->get()->pluck('destination_id');

        foreach($systems as $system) {
            GetJumps::dispatch($system, 'shortest');
            GetJumps::dispatch($system, 'secure');
            GetJumps::dispatch($system, 'insecure');
        }
    }
}
