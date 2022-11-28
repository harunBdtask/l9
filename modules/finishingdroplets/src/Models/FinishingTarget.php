<?php

namespace SkylarkSoft\GoRMG\Finishingdroplets\Models;

use App\FactoryIdTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use SkylarkSoft\GoRMG\Merchandising\Models\Order;
use SkylarkSoft\GoRMG\SystemSettings\Models\Buyer;
use SkylarkSoft\GoRMG\SystemSettings\Models\FinishingFloor;
use SkylarkSoft\GoRMG\SystemSettings\Models\FinishingTable;
use SkylarkSoft\GoRMG\SystemSettings\Models\GarmentsItem;

class FinishingTarget extends Model
{
    use SoftDeletes, FactoryIdTrait;

    protected $fillable = [
        'production_date',
        'factory_id',
        'finishing_floor_id',
        'finishing_table_id',
        'buyer_id',
        'order_id',
        'item_id',
        'item_group',
        'iron_work_hour',
        'iron_man_power',
        'iron_smv',
        'iron_per_man_target',
        'iron_hour_target',
        'iron_day_total_target',
        'poly_work_hour',
        'poly_man_power',
        'poly_smv',
        'poly_per_man_target',
        'poly_hour_target',
        'poly_day_total_target',
        'packing_work_hour',
        'packing_man_power',
        'packing_smv',
        'packing_per_man_target',
        'packing_hour_target',
        'packing_day_total_target',
        'total_efficiency',
        'created_at',
        'updated_at'
    ];

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
}
