<?php

namespace SkylarkSoft\GoRMG\Inventory\Models;

use App\ModelCommonTrait;
use Awobaz\Compoships\Compoships;
use Illuminate\Database\Query\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use SkylarkSoft\GoRMG\SystemSettings\Models\Supplier;
use SkylarkSoft\GoRMG\SystemSettings\Models\YarnType;
use SkylarkSoft\GoRMG\SystemSettings\Models\YarnCount;
use SkylarkSoft\GoRMG\SystemSettings\Models\CompositionType;
use SkylarkSoft\GoRMG\SystemSettings\Models\YarnComposition;
use SkylarkSoft\GoRMG\SystemSettings\Models\UnitOfMeasurement;

/**
 * @property $yarn_receive_id
 * @property $yarn_count_id
 * @property $yarn_composition_id
 * @property $yarn_type_id
 * @property $yarn_color
 * @property $yarn_lot
 * @property $buyer_id
 * @property $receive_qty
 * @property $yarn_brand
 * @property $uom_id
 * @property $rate
 * @property $amount
 * @property $ile_percentage
 * @property $balance_qty
 * @property $book_currency
 * @property $no_of_bag
 * @property $no_of_cone_per_bag
 * @property $over_receive_qty
 * @property $no_of_loose_cone
 * @property $weight_per_bag
 * @property $weight_per_cone
 * @property $product_code
 * @property $floor_id
 * @property $room_id
 * @property $rack_id
 * @property $shelf_id
 * @property $bin_id
 * @property $remarks
 */
class YarnReceiveDetail extends Model
{
    use SoftDeletes, ModelCommonTrait;
    use Compoships;

    protected $primaryKey = 'id';
    protected $table = 'yarn_receive_details';
    protected $fillable = [
        'yarn_count_id',
        'yarn_receive_id',
        'yarn_composition_id',
        'yarn_type_id',
        'yarn_color',
        'supplier_id',
        'yarn_lot',
        'buyer_id',
        'store_id',
        'receive_qty',
        'yarn_brand',
        'uom_id',
        'rate',
        'amount',
        'no_of_bag',
        'no_of_box',
        'balance_qty',
        'book_currency',
        'basis_details_unique',
        'no_of_cone_per_bag',
        'over_receive_qty',
        'no_of_loose_cone',
        'weight_per_bag',
        'weight_per_cone',
        'product_code',
        'floor_id',
        'shelf_id',
        'room_id',
        'remarks',
        'rack_id',
        'bin_id',
        'certification',
        'origin',
        'created_by',
        'updated_by',
        'deleted_by',
        'deleted_at',
    ];
    public static function booted()
    {
        static::created(function ($model) {
            $model->product_code = getPrefix() . 'YS-' . date('Y-m') . '-' . str_pad($model->id, 6, '0', STR_PAD_LEFT);
            $model->save();
        });
    }

    public function supplier(): BelongsTo
    {
        return $this->belongsTo(Supplier::class, 'supplier_id')->withDefault();
    }

    public function yarnReceive(): BelongsTo
    {
        return $this->belongsTo(YarnReceive::class,'yarn_receive_id')->withDefault();
    }

    public function yarnReceiveToTouch(): BelongsTo
    {
        return $this->yarnReceive()->withoutGlobalScope('approvalMaintain');
    }

    public function uom(): BelongsTo
    {
        return $this->belongsTo(UnitOfMeasurement::class, 'uom_id')->withDefault();
    }

    public function floor(): BelongsTo
    {
        return $this->belongsTo(StoreFloor::class, 'floor_id')->withDefault();
    }

    public function room(): BelongsTo
    {
        return $this->belongsTo(StoreRoom::class, 'room_id')->withDefault();
    }

    public function rack(): BelongsTo
    {
        return $this->belongsTo(StoreRack::class, 'rack_id')->withDefault();
    }

    public function shelf(): BelongsTo
    {
        return $this->belongsTo(StoreShelf::class, 'shelf_id')->withDefault();
    }

    public function composition(): BelongsTo
    {
        return $this->belongsTo(YarnComposition::class, 'yarn_composition_id')->withDefault();
    }

    public function yarn_count(): BelongsTo
    {
        return $this->belongsTo(YarnCount::class, 'yarn_count_id')->withDefault();
    }

    public function type(): BelongsTo
    {
        return $this->belongsTo(CompositionType::class, 'yarn_type_id')->withDefault();
    }

    public function bin(): BelongsTo
    {
        return $this->belongsTo(StoreBin::class, 'bin_id')->withDefault();
    }

    public function stockSummery(): HasOne
    {
        return $this->hasOne(YarnStockSummary::class, 'yarn_lot', 'yarn_lot');
    }
}
