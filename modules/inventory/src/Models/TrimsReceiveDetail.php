<?php

namespace SkylarkSoft\GoRMG\Inventory\Models;

use App\Casts\Json;
use App\Models\UIDModel;
use Illuminate\Database\Eloquent\SoftDeletes;
use SkylarkSoft\GoRMG\Merchandising\Models\Order;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use SkylarkSoft\GoRMG\SystemSettings\Models\ItemGroup;
use SkylarkSoft\GoRMG\SystemSettings\Models\UnitOfMeasurement;

class TrimsReceiveDetail extends UIDModel
{
    use SoftDeletes;

    protected $table = 'trims_receive_details';

    protected $fillable = [
        'uniq_id',
        'order_uniq_id',
        'trims_receive_id',
        'ship_date',
        'style_name',
        'po_no',
        'ref_no',
        'brand_sup_ref',
        'item_id',
        'item_description',
        'gmts_sizes',
        'item_color',
        'item_size',
        'uom_id',
        'wo_pi_qty',
        'receive_qty',
        'rate',
        'amount',
        'reject_qty',
        'payment_for_over_receive_qty',
        'floor',
        'room',
        'rack',
        'shelf',
        'bin',
    ];

    protected $casts = [
        'po_no' => Json::class,
        'gmts_sizes' => Json::class
    ];

    public function trimsReceive(): BelongsTo
    {
        return $this->belongsTo(TrimsReceive::class, 'trims_receive_id')->withDefault();
    }

    public static function getConfig(): array
    {
        return [
            'abbr' => 'TRD'
        ];
    }

    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class, 'style_name', 'style_name')->withDefault();
    }

    public function uom(): BelongsTo
    {
        return $this->belongsTo(UnitOfMeasurement::class, 'uom_id')->withDefault();
    }

    public function trimsItem(): BelongsTo
    {
        return $this->belongsTo(ItemGroup::class, 'item_id')->withDefault();
    }

    public function setAmountAttribute()
    {
        $this->attributes['amount'] = $this->rate * $this->receive_qty;
    }

    public function floorDetail(): BelongsTo
    {
        return $this->belongsTo(StoreFloor::class, 'floor')->withDefault();
    }

    public function roomDetail(): BelongsTo
    {
        return $this->belongsTo(StoreRoom::class, 'room')->withDefault();
    }

    public function rackDetail(): BelongsTo
    {
        return $this->belongsTo(StoreRack::class, 'rack')->withDefault();
    }

    public function shelfDetail(): BelongsTo
    {
        return $this->belongsTo(StoreShelf::class, 'shelf')->withDefault();
    }

    public function binDetail(): BelongsTo
    {
        return $this->belongsTo(StoreBin::class, 'bin')->withDefault();
    }
}
