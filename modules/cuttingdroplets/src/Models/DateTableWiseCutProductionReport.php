<?php

namespace SkylarkSoft\GoRMG\Cuttingdroplets\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\FactoryIdTrait;
use SkylarkSoft\GoRMG\Iedroplets\Models\CuttingTarget;
use SkylarkSoft\GoRMG\Merchandising\Models\PurchaseOrder;
use SkylarkSoft\GoRMG\Merchandising\Models\PurchaseOrderDetail;
use SkylarkSoft\GoRMG\SystemSettings\Models\GarmentsItem;

class DateTableWiseCutProductionReport extends Model
{
    use FactoryIdTrait, SoftDeletes;

    protected $table = 'date_table_wise_cut_production_reports';

    protected $fillable = [
        'production_date',
        'cutting_floor_id',
        'cutting_table_id',
        'buyer_id',
        'order_id',
        'garments_item_id',
        'purchase_order_id',
        'color_id',
        'size_id',
        'cutting_qty',
        'cutting_rejection_qty',
        'factory_id'
    ];

    protected $dates = [
        'deleted_at'
    ];

    public function cuttingFloor(): BelongsTo
    {
        return $this->belongsTo('SkylarkSoft\GoRMG\SystemSettings\Models\CuttingFloor', 'cutting_floor_id', 'id')->withDefault();
    }

    public function cuttingTable(): BelongsTo
    {
        return $this->belongsTo('SkylarkSoft\GoRMG\SystemSettings\Models\CuttingTable', 'cutting_table_id', 'id')->withDefault();
    }

    public function buyer(): BelongsTo
    {
        return $this->belongsTo('SkylarkSoft\GoRMG\SystemSettings\Models\Buyer', 'buyer_id')->withDefault();
    }

    public function buyerWithoutGlobalScopes(): BelongsTo
    {
        return $this->belongsTo('SkylarkSoft\GoRMG\SystemSettings\Models\Buyer', 'buyer_id')->withoutGlobalScopes();
    }

    public function order(): BelongsTo
    {
        return $this->belongsTo('SkylarkSoft\GoRMG\Merchandising\Models\Order', 'order_id')->withDefault();
    }

    public function orderWithoutGlobalScopes(): BelongsTo
    {
        return $this->belongsTo('SkylarkSoft\GoRMG\Merchandising\Models\Order', 'order_id')->withoutGlobalScopes();
    }

    public function garmentsItem(): BelongsTo
    {
        return $this->belongsTo(GarmentsItem::class, 'garments_item_id')->withDefault();
    }

    public function purchaseOrder(): BelongsTo
    {
        return $this->belongsTo('SkylarkSoft\GoRMG\Merchandising\Models\PurchaseOrder', 'purchase_order_id')->withDefault();
    }

    public function purchaseOrderWithoutGlobalScopes(): BelongsTo
    {
        return $this->belongsTo('SkylarkSoft\GoRMG\Merchandising\Models\PurchaseOrder', 'purchase_order_id')->withoutGlobalScopes();
    }

    public function color(): BelongsTo
    {
        return $this->belongsTo('SkylarkSoft\GoRMG\SystemSettings\Models\Color', 'color_id')->withDefault();
    }

    public function colorWithoutGlobalScopes(): BelongsTo
    {
        return $this->belongsTo('SkylarkSoft\GoRMG\SystemSettings\Models\Color', 'color_id')->withoutGlobalScopes();
    }

    public function size(): BelongsTo
    {
        return $this->belongsTo('SkylarkSoft\GoRMG\SystemSettings\Models\Size', 'size_id')->withDefault();
    }

    public function sizeWithoutGlobalScopes()
    {
        return $this->belongsTo('SkylarkSoft\GoRMG\SystemSettings\Models\Size', 'size_id')->withoutGlobalScopes();
    }

    public function factory(): BelongsTo
    {
        return $this->belongsTo('SkylarkSoft\GoRMG\SystemSettings\Models\Factory', 'factory_id')->withDefault();
    }

    public function factoryWithoutGlobalScopes(): BelongsTo
    {
        return $this->belongsTo('SkylarkSoft\GoRMG\SystemSettings\Models\Factory', 'factory_id')->withoutGlobalScopes();
    }

    public function purchaseOrderDetail(): HasManyThrough
    {
        return $this->hasManyThrough(
            PurchaseOrderDetail::class,
            PurchaseOrder::class,
            'id',
            'purchase_order_id',
            'purchase_order_id'
        );
    }

    public static function cuttingTarget($cutting_table_id, $date)
    {
        return CuttingTarget::where('cutting_table_id', $cutting_table_id)
            ->whereDate('target_date', $date)
            ->first()->target ?? 0;
    }

    public static function dateRangeWiseCuttingTarget($factory_id, $from_date = null, $to_date = null)
    {
        return CuttingTarget::where('factory_id', $factory_id)
            ->when(($from_date && $to_date), function ($query) use ($from_date, $to_date) {
                $query->whereDate('target_date', '>=', $from_date)
                    ->whereDate('target_date', '<=', $to_date);
            })
            ->sum('target') ?? 0;
    }

    public static function todaysFactoryCutting($factory_id)
    {
        return self::where('factory_id', $factory_id)
            ->selectRaw("SUM(cutting_qty) as cutting_qty, SUM(cutting_rejection_qty) as cutting_rejection_qty")
            ->whereDate('production_date', now()->toDateString())
            ->groupBy('factory_id')
            ->first();
    }
}
