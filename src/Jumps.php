<?php

namespace Clanofartisans\EveEsi;


use Clanofartisans\EveEsi\Models\Stargate;
use Fisharebest\Algorithm\Dijkstra;
use Illuminate\Support\Arr;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;

class Jumps
{
    /**
     * The string to use for caching this model.
     *
     * @var string
     */
    protected string $cacheKey = 'esi_jumps_cache';

    /**
     * @var int
     */
    protected int $from;

    /**
     * @var int|array
     */
    protected int|array $to;

    /**
     * @var string
     */
    protected string $flag = 'shortest';

    /**
     * @var int|array
     */
    protected int|array $avoid;

    /**
     * @param int $from
     */
    public function __construct(int $from)
    {
        $this->from = $from;
    }

    /**
     * @param int $from
     * @return Jumps
     */
    public static function from(int $from): Jumps
    {
        return new Jumps($from);
    }

    /**
     * @param int|array $to
     * @return Jumps $this
     */
    public function to(int|array $to): Jumps
    {
        $this->to = $to;
        return $this;
    }

    /**
     * @return Jumps $this
     */
    public function shortest(): Jumps
    {
        $this->flag = 'shortest';

        return $this;
    }

    /**
     * @return Jumps $this
     */
    public function secure(): Jumps
    {
        $this->flag = 'secure';

        return $this;
    }

    /**
     * @return Jumps $this
     */
    public function insecure(): Jumps
    {
        $this->flag = 'insecure';

        return $this;
    }

    /**
     * @param int|array $avoid
     * @return Jumps $this
     */
    public function avoid(int|array $avoid): Jumps
    {
        $this->avoid = $avoid;

        return $this;
    }

    /**
     * @return array
     */
    public function path(): array
    {
        return ['not yet implemented'];
    }

    /**
     * @return int[]
     */
    public function count(): array
    {
        $dijkstra = $this->dijkstra($this->cGraph());

        $routes = [];

        foreach($this->destinations() as $destination) {
            $route = $dijkstra->shortestPaths($this->from, $destination);
            if(!empty($route)) {
                $routes[$destination] = count($route[0]) - 1;
            }
        }

        return $routes;
    }

    /**
     * @return int[][]
     */
    protected function cGraph(): array
    {
        $key = $this->cacheKey.':cGraph:'.$this->flag;

        if(Cache::has($key)) {
            $graph = Cache::get($key);
        } else {
            $stargates = Stargate::select(['origin_id', 'destination_id', 'secure'])
                ->get();

            $graph = [];

            foreach ($stargates as $stargate) {
                if (($this->flag === 'secure' && !$stargate->secure) || ($this->flag === 'insecure' && $stargate->secure)) {
                    $graph[$stargate->destination_id][$stargate->origin_id] = 50000;
                } else {
                    $graph[$stargate->destination_id][$stargate->origin_id] = 1;
                }
            }

            Cache::forever($key, $graph);
        }

        if(isset($this->avoid)) {
            $graph = Arr::except($graph, $this->avoided());
        }

        return $graph;
    }

    /**
     * @param int[][] $graph
     * @return Dijkstra
     */
    protected function dijkstra(array $graph): Dijkstra
    {
        return new Dijkstra($graph);
    }

    /**
     * @return Collection
     */
    protected function destinations(): Collection
    {
        if(!empty($this->to)) {
            return collect($this->to);
        }

        return collect(Stargate::select(['destination_id'])->distinct()->get()->pluck('destination_id'));
    }

    /**
     * @return int[]
     */
    protected function avoided(): array
    {
        return Arr::wrap($this->avoid);
    }
}
