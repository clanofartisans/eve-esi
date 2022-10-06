<?php

namespace Clanofartisans\EveEsi\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Collection;

class MarketOrder extends ESIModel
{
    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'issued' => 'datetime'
    ];

    /**
     * The primary key for the model.
     *
     * @var string
     */
    protected $primaryKey = 'order_id';

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'esi_market_orders';

    /**
     * New
     *
     * @param string $section
     * @param Collection $updates
     * @return void
     */
    public function createFromJson(string $section, Collection $updates): void
    {
        foreach($updates as $update) {
            $order_type = $update->data['is_buy_order'] ? 'buy' : 'sell';
            $location_type = $update->data['location_id'] > 1000000000000 ? 'structure' : 'station';

            $this->upsert([
                'order_id' => $update->data_id,
                'order_type' => $order_type,
                'region_id' => $section,
                'system_id' => $update->data['system_id'],
                'location_type' => $location_type,
                'location_id' => $update->data['location_id'],
                'type_id' => $update->data['type_id'],
                'price' => $update->data['price'],
                'range' => $update->data['range'],
                'duration' => $update->data['duration'],
                'issued' => new Carbon($update->data['issued']),
                'min_volume' => $update->data['min_volume'],
                'volume_remain' => $update->data['volume_remain'],
                'volume_total' => $update->data['volume_total'],
                'hash' => $update->hash
            ], ['order_id'], [
                'price',
                'issued',
                'volume_remain',
                'hash'
            ]);
        }
    }

    /**
     * New
     *
     * @param string $section
     * @return $this
     */
    public function whereSection(string $section)
    {
        return $this->where('region_id', $section);
    }

    /**
     * New
     *
     * @return BelongsTo
     */
    public function location(): BelongsTo
    {
        return $this->belongsTo(Location::class, 'location_id', 'location_id');
    }
}
