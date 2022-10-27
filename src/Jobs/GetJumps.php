<?php

namespace Clanofartisans\EveEsi\Jobs;

use Clanofartisans\EveEsi\Jumps;
use Clanofartisans\EveEsi\Models\JumpRoute;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class GetJumps implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * New
     *
     * @var int
     */
    protected int $origin;

    /**
     * New
     *
     * @var string
     */
    protected string $security;

    /**
     * The number of seconds the job can run before timing out.
     *
     * @var int
     */
    public int $timeout = 3600;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(int $origin, string $security)
    {
        $this->origin = $origin;
        $this->security = $security;
    }

    /**
     * New
     *
     * @return void
     */
    public function handle(): void
    {
        $jumps = Jumps::from($this->origin);
        $jumps = match ($this->security) {
            'shortest' => $jumps->shortest(),
            'secure' => $jumps->secure(),
            'insecure' => $jumps->insecure(),
        };
        $jumps = $jumps->count();

        $insert = [];
        foreach($jumps as $destination => $count) {
            $insert[] = [
                'origin_id' => $this->origin,
                'destination_id' => $destination,
                'security' => $this->security,
                'jumps' => $count
            ];
        }

        JumpRoute::insert($insert);
    }
}
