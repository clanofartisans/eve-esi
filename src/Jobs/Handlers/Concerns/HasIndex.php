<?php

namespace Clanofartisans\EveEsi\Jobs\Handlers\Concerns;

use Clanofartisans\EveEsi\Auth\RefreshTokenException;
use Clanofartisans\EveEsi\Jobs\ESIFetchData;
use Clanofartisans\EveEsi\Models\ESITableUpdates;
use Clanofartisans\EveEsi\Routes\ESIRoute;
use Clanofartisans\EveEsi\Routes\InvalidESIResponseException;
use Illuminate\Support\Collection;

trait HasIndex
{
    /**
     * New - Maybe note the $page/$id thing
     *
     * @param string $section
     * @param int $page
     * @return void
     * @throws InvalidESIResponseException
     * @throws RefreshTokenException
     */
    public function fetchData(string $section, int $page): void
    {
        $this->section = $section;

        $data = $this->resourceRoute($page)->get()->body();

        $update = [
            'table' => $this->updateTable,
            'section' => $this->section,
            'data_id' => $page,
            'hash' => md5($data),
            'data' => $data
        ];

        ESITableUpdates::insert($update);
    }

    /**
     * New
     *
     * @return array
     * @throws InvalidESIResponseException
     * @throws RefreshTokenException
     */
    protected function buildFetchBatch(): array
    {
        $index = $this->fetchIndex();
        $batch = [];
        foreach($index as $id) {
            $batch[] = new ESIFetchData($this::class, $this->section, $id);
        }

        return $batch;
    }

    /**
     * New
     *
     * @return Collection
     * @throws InvalidESIResponseException
     * @throws RefreshTokenException
     */
    protected function fetchIndex(): Collection
    {
        $pages = $this->pages();
        $index = collect([]);
        for($i = 1; $i <= $pages; $i++) {
            $index = $index->merge($this->indexRoute()->page($i)->get()->json());
        }

        return $index;
    }

    /**
     * New
     *
     * @return int
     */
    protected function pages(): int
    {
        return $this->indexRoute()->getNumPages();
    }

    /**
     * New - Per Handler
     *
     * @return ESIRoute
     */
    abstract protected function indexRoute(): ESIRoute;

    /**
     * New - Per Handler
     *
     * @param int $id
     * @return ESIRoute
     */
    abstract protected function resourceRoute(int $id): ESIRoute;
}
