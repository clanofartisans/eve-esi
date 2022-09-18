<?php

namespace Clanofartisans\EveEsi\Models;

class MarketOrder extends ESIModel
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'esi_market_orders';

    /**
     * The primary key for the model.
     *
     * @var string
     */
    protected $primaryKey = 'order_id';

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'issued' => 'datetime'
    ];

    /**
     * Creates a record, or updates an existing record, from JSON data.
     *
     * @param int $id
     * @param array $data
     * @param string $hash
     * @return ESIModel
     */
    public function createFromJson(int $id, array $data, string $hash): ESIModel
    {
        $data['order_type'] = $data['is_buy_order'] ? 'buy' : 'sell';
        $data['location_type'] = $data['location_id'] > 1000000000000 ? 'structure' : 'station';

        return $this->updateOrCreate([
            'order_id' => $id
        ], [
            'order_type' => $data['order_type'],
            'region_id' => $data['region_id'],
            'system_id' => $data['system_id'],
            'location_type' => $data['location_type'],
            'location_id' => $data['location_id'],
            'type_id' => $data['type_id'],
            'price' => $data['price'],
            'range' => $data['range'],
            'duration' => $data['duration'],
            'issued' => $data['issued'],
            'min_volume' => $data['min_volume'],
            'volume_remain' => $data['volume_remain'],
            'volume_total' => $data['volume_total'],
            'hash' => $hash
        ]);
    }
}
