<?php

namespace Clanofartisans\EveEsi\Routes;

class Universe extends ESIRoute
{
    /**
     * Handles the /universe/ancestries path.
     *
     * @return Universe $this
     */
    public function ancestries(): Universe
    {
        $this->route = 'universe.ancestries';

        return $this;
    }

    /**
     * Handles the /universe/categories path.
     *
     * @return Universe $this
     */
    public function categories(): Universe
    {
        $this->route = 'universe.categories';

        return $this;
    }

    /**
     * Calls the appropriate /{category_id} endpoint.
     *
     * @param int $categoryID
     * @return Universe $this
     */
    public function category(int $categoryID): Universe
    {
        return $this->categoriesCategory($categoryID);
    }

    /**
     * Handles the /universe/categories/{category_id} path.
     *
     * @param int $categoryID
     * @return Universe $this
     */
    protected function categoriesCategory(int $categoryID): Universe
    {
        $this->route = 'universe.categories.category';

        $this->parameters['path']['category_id'] = $categoryID;

        return $this;
    }

    /**
     * Handles the /universe/constellations path.
     *
     * @return Universe $this
     */
    public function constellations(): Universe
    {
        $this->route = 'universe.constellations';

        return $this;
    }

    /**
     * Calls the appropriate /{constellation_id} endpoint.
     *
     * @param int $constellationID
     * @return Universe $this
     */
    public function constellation(int $constellationID): Universe
    {
        return $this->constellationsConstellation($constellationID);
    }

    /**
     * Handles the /universe/constellations/{constellation_id} path.
     *
     * @param int $constellationID
     * @return Universe $this
     */
    protected function constellationsConstellation(int $constellationID): Universe
    {
        $this->route = 'universe.constellations.constellation';

        $this->parameters['path']['constellation_id'] = $constellationID;

        return $this;
    }

    /**
     * Handles the /universe/groups path.
     *
     * @return Universe $this
     */
    public function groups(): Universe
    {
        $this->route = 'universe.groups';

        return $this;
    }

    /**
     * Calls the appropriate /{group_id} endpoint.
     *
     * @param int $groupID
     * @return Universe $this
     */
    public function group(int $groupID): Universe
    {
        return $this->groupsGroup($groupID);
    }

    /**
     * Handles the /universe/groups/{group_id} path.
     *
     * @param int $groupID
     * @return Universe $this
     */
    protected function groupsGroup(int $groupID): Universe
    {
        $this->route = 'universe.groups.group';

        $this->parameters['path']['group_id'] = $groupID;

        return $this;
    }

    /**
     * Handles the /universe/regions path.
     *
     * @return Universe $this
     */
    public function regions(): Universe
    {
        $this->route = 'universe.regions';

        return $this;
    }

    /**
     * Calls the appropriate /{region_id} endpoint.
     *
     * @param int $regionID
     * @return Universe $this
     */
    public function region(int $regionID): Universe
    {
        return $this->regionsRegion($regionID);
    }

    /**
     * Handles the /universe/regions/{region_id} path.
     *
     * @param int $regionID
     * @return Universe $this
     */
    protected function regionsRegion(int $regionID): Universe
    {
        $this->route = 'universe.regions.region';

        $this->parameters['path']['region_id'] = $regionID;

        return $this;
    }

    /**
     * Begins the /universe/stations path.
     *
     * @return Universe $this
     */
    public function stations(): Universe
    {
        $this->route = 'universe.stations';

        return $this;
    }

    /**
     * Calls the appropriate /{station_id} endpoint.
     *
     * @param int $stationID
     * @return Universe $this
     */
    public function station(int $stationID): Universe
    {
        return $this->stationsStation($stationID);
    }

    /**
     * Handles the /universe/stations/{station_id} path.
     *
     * @param int $stationID
     * @return Universe $this
     */
    protected function stationsStation(int $stationID): Universe
    {
        $this->route = 'universe.stations.station';

        $this->parameters['path']['station_id'] = $stationID;

        return $this;
    }

    /**
     * Handles the /universe/structures path.
     *
     * @return Universe $this
     */
    public function structures(): Universe
    {
        $this->route = 'universe.structures';

        return $this;
    }

    /**
     * Calls the appropriate /{structure_id} endpoint.
     *
     * @param int $structureID
     * @return Universe $this
     */
    public function structure(int $structureID): Universe
    {
        return $this->structuresStructure($structureID);
    }

    /**
     * Handles the /universe/structures/{structure_id} path.
     *
     * @param int $structureID
     * @return Universe $this
     */
    protected function structuresStructure(int $structureID): Universe
    {
        $this->route = 'universe.structures.structure';

        $this->parameters['path']['structure_id'] = $structureID;

        return $this;
    }

    /**
     * Handles the /universe/systems path.
     *
     * @return Universe $this
     */
    public function systems(): Universe
    {
        $this->route = 'universe.systems';

        return $this;
    }

    /**
     * Calls the appropriate /{system_id} endpoint.
     *
     * @param int $systemID
     * @return Universe $this
     */
    public function system(int $systemID): Universe
    {
        return $this->systemsSystem($systemID);
    }

    /**
     * Handles the /universe/systems/{system_id} path.
     *
     * @param int $systemID
     * @return Universe $this
     */
    protected function systemsSystem(int $systemID): Universe
    {
        $this->route = 'universe.systems.system';

        $this->parameters['path']['system_id'] = $systemID;

        return $this;
    }

    /**
     * Handles the /universe/types path.
     *
     * @return Universe $this
     */
    public function types(): Universe
    {
        $this->route = 'universe.types';

        return $this;
    }

    /**
     * Calls the appropriate /{type_id} endpoint.
     *
     * @param int $typeID
     * @return Universe $this
     */
    public function type(int $typeID): Universe
    {
        return $this->typesType($typeID);
    }

    /**
     * Handles the /universe/types/{type_id} path.
     *
     * @param int $typeID
     * @return Universe $this
     */
    protected function typesType(int $typeID): Universe
    {
        $this->route = 'universe.types.type';

        $this->parameters['path']['type_id'] = $typeID;

        return $this;
    }

    /**
     * Builds the URI for the request.
     *
     * @return string
     */
    protected function uri(): string
    {
        $uri = match ($this->route) {
            'universe.ancestries' =>
                '/universe/ancestries',
            'universe.categories' =>
                '/universe/categories',
            'universe.categories.category' =>
                '/universe/categories/' . $this->parameters['path']['category_id'],
            'universe.constellations' =>
                '/universe/constellations',
            'universe.constellations.constellation' =>
                '/universe/constellations/' . $this->parameters['path']['constellation_id'],
            'universe.groups' =>
                '/universe/groups',
            'universe.groups.group' =>
                '/universe/groups/' . $this->parameters['path']['group_id'],
            'universe.regions' =>
                '/universe/regions',
            'universe.regions.region' =>
                '/universe/regions/' . $this->parameters['path']['region_id'],
            'universe.stations.station' =>
                '/universe/stations/' . $this->parameters['path']['station_id'],
            'universe.structures' =>
                '/universe/structures',
            'universe.structures.structure' =>
                '/universe/structures/' . $this->parameters['path']['structure_id'],
            'universe.systems' =>
                '/universe/systems',
            'universe.systems.system' =>
                '/universe/systems/' . $this->parameters['path']['system_id'],
            'universe.types' =>
                '/universe/types',
            'universe.types.type' =>
                '/universe/types/' . $this->parameters['path']['type_id'],
            default => ''
        };

        return $this->baseURI.$uri;
    }
}
