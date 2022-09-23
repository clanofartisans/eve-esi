<?php

namespace Clanofartisans\EveEsi\Jobs\Handlers\Concerns;

use Clanofartisans\EveEsi\Auth\RefreshTokenException;
use Clanofartisans\EveEsi\Jobs\ESIFetchData;
use Clanofartisans\EveEsi\Models\ESITableUpdates;
use Clanofartisans\EveEsi\Routes\ESIRoute;
use Clanofartisans\EveEsi\Routes\InvalidESIResponseException;

trait NoIndex
{
    /**
     * New
     *
     * @return array
     */
    protected function buildFetchBatch(): array
    {
        $pages = $this->pages();
        $batch = [];
        for($i = 1; $i <= $pages; $i++) {
            $batch[] = new ESIFetchData($this::class, $this->section, $i);
        }

        return $batch;
    }

    /**
     * New
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

        $data = $this->baseRoute()->page($page)->get()->collect();

        foreach($data->chunk(50) as $chunk) {
            $updates = [];
            foreach($chunk as $datum) {
                $hash = md5(json_encode($datum));

                $updates[] = [
                    'table' => $this->updateTable,
                    'section' => $this->section,
                    'data_id' => $datum[$this->esiIDName],
                    'hash' => $hash,
                    'data' => json_encode($datum)
                ];
            }
            ESITableUpdates::insert($updates);
        }
    }

    /**
     * New
     *
     * @return int
     */
    protected function pages(): int
    {
        return $this->baseRoute()->getNumPages();
    }

    /**
    * New - Per Handler
    *
    * @return ESIRoute
    */
    abstract protected function baseRoute(): ESIRoute;
}
