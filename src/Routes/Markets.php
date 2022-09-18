<?php

namespace Clanofartisans\EveEsi\Routes;

class Markets extends ESIRoute
{
    /**
     * Handles the /markets/{region_id} path.
     *
     * @param int $regionID
     * @return Markets $this
     */
    public function region(int $regionID): Markets
    {
        $this->route = 'markets.region';

        $this->parameters['path']['region_id'] = $regionID;

        return $this;
    }

    /**
     * Calls the appropriate /history endpoint.
     *
     * @param int $typeID
     * @return Markets $this
     */
    public function history(int $typeID): Markets
    {
        return $this->regionHistory($typeID);
    }

    /**
     * Handles the /markets/{region_id}/history path.
     *
     * @param int $typeID
     * @return Markets $this
     */
    protected function regionHistory(int $typeID): Markets
    {
        $this->route = 'markets.region.history';

        $this->parameters['query']['type_id'] = $typeID;

        return $this;
    }

    /**
     * Calls the appropriate /orders endpoint.
     *
     * @param ?int $typeID
     * @param string $order_type
     * @return Markets $this
     */
    public function orders(int $typeID = null, string $order_type = 'all'): Markets
    {
        return $this->regionOrders($typeID, $order_type);
    }

    /**
     * Handles the /markets/{region_id}/orders path.
     *
     * @param ?int $typeID
     * @param string $order_type
     * @return Markets $this
     */
    protected function regionOrders(int $typeID = null, string $order_type = 'all'): Markets
    {
        $this->route = 'markets.region.orders';

        $this->parameters['query']['type_id'] = $typeID;
        $this->parameters['query']['order_type'] = $order_type;

        return $this;
    }

    /**
     * Calls the appropriate /types endpoint.
     *
     * @return Markets $this
     */
    public function types(): Markets
    {
        return $this->regionTypes();
    }

    /**
     * Handles the /markets/{region_id}/types path.
     *
     * @return Markets $this
     */
    protected function regionTypes(): Markets
    {
        $this->route = 'markets.region.types';

        return $this;
    }

    /**
     * Handles the /markets/groups path.
     *
     * @return Markets $this
     */
    public function groups(): Markets
    {
        $this->route = 'markets.groups';

        return $this;
    }

    /**
     * Calls the appropriate /{market_group_id} endpoint.
     *
     * @param int $marketGroupID
     * @return Markets $this
     */
    public function market_group(int $marketGroupID): Markets
    {
        return $this->groupsMarketGroups($marketGroupID);
    }

    /**
     * Handles the /markets/groups/{market_group_id} path.
     *
     * @param int $marketGroupID
     * @return Markets $this
     */
    protected function groupsMarketGroups(int $marketGroupID): Markets
    {
        $this->route = 'markets.groups.market_group';

        $this->parameters['path']['market_group_id'] = $marketGroupID;

        return $this;
    }

    /**
     * Handles the /markets/prices path.
     *
     * @return Markets $this
     */
    public function prices(): Markets
    {
        $this->route = 'markets.prices';

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
            'markets.region.history' =>
                '/markets/' . $this->parameters['path']['region_id'] . '/history',
            'markets.region.orders' =>
                '/markets/' . $this->parameters['path']['region_id'] . '/orders',
            'markets.region.types' =>
                '/markets/' . $this->parameters['path']['region_id'] . '/types',
            'markets.prices' =>
                '/markets/prices',
            'markets.groups' =>
                '/markets/groups',
            'markets.groups.market_group' =>
                '/markets/groups/' . $this->parameters['path']['market_group_id'],
            default => ''
        };

        return $this->baseURI.$uri;
    }
}
