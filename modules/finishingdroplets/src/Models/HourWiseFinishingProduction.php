<?php

namespace SkylarkSoft\GoRMG\Finishingdroplets\Models;

use App\FactoryIdTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use SkylarkSoft\GoRMG\Merchandising\Models\Order;
use SkylarkSoft\GoRMG\Merchandising\Models\PurchaseOrder;
use SkylarkSoft\GoRMG\Merchandising\Models\PurchaseOrderDetail;
use SkylarkSoft\GoRMG\SystemSettings\Models\Buyer;
use SkylarkSoft\GoRMG\SystemSettings\Models\Color;
use SkylarkSoft\GoRMG\SystemSettings\Models\FinishingFloor;
use SkylarkSoft\GoRMG\SystemSettings\Models\FinishingTable;
use SkylarkSoft\GoRMG\SystemSettings\Models\GarmentsItem;

class HourWiseFinishingProduction extends Model
{
    use SoftDeletes, FactoryIdTrait;

    protected $table = "hour_wise_finishing_productions";
    protected $fillable = [
        'production_date',
        'factory_id',
        'finishing_floor_id',
        'finishing_table_id',
        'buyer_id',
        'order_id',
        'item_id',
        'po_id',
        'color_id',
        'production_type',
        'hour_0',
        'hour_1',
        'hour_2',
        'hour_3',
        'hour_4',
        'hour_5',
        'hour_6',
        'hour_7',
        'hour_8',
        'hour_9',
        'hour_10',
        'hour_11',
        'hour_12',
        'hour_13',
        'hour_14',
        'hour_15',
        'hour_16',
        'hour_17',
        'hour_18',
        'hour_19',
        'hour_20',
        'hour_21',
        'hour_22',
        'hour_23',
        'created_by',
        'updated_by',
        'deleted_by',
    ];

    const IRON = 'iron';
    const IRON_REJECTION = 'iron_rejection';
    const POLY = 'poly';
    const POLY_REJECTION = 'poly_rejection';
    const PACKING = 'packing';
    const PACKING_REJECTION = 'packing_rejection';
    const REASON = 'reason';

    public function floor(): BelongsTo
    {
        return $this->belongsTo(FinishingFloor::class, 'finishing_floor_id')->withDefault();
    }

    public function table(): BelongsTo
    {
        return $this->belongsTo(FinishingTable::class, 'finishing_table_id')->withDefault();
    }

    public function buyer(): BelongsTo
    {
        return $this->belongsTo(Buyer::class, 'buyer_id')->withDefault();
    }

    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class, 'order_id')->withDefault();
    }

    public function item(): BelongsTo
    {
        return $this->belongsTo(GarmentsItem::class, 'item_id')->withDefault();
    }

    public function purchaseOrder(): BelongsTo
    {
        return $this->belongsTo(PurchaseOrder::class, 'po_id')->withDefault();
    }

    public function color(): BelongsTo
    {
        return $this->belongsTo(Color::class, 'color_id')->withDefault();
    }
}
