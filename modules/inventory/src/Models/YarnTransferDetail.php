<?php

namespace SkylarkSoft\GoRMG\Inventory\Models;

use App\ModelCommonTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use SkylarkSoft\GoRMG\SystemSettings\Models\CompositionType;
use SkylarkSoft\GoRMG\SystemSettings\Models\UnitOfMeasurement;
use SkylarkSoft\GoRMG\SystemSettings\Models\YarnComposition;
use SkylarkSoft\GoRMG\SystemSettings\Models\YarnCount;
use SkylarkSoft\GoRMG\SystemSettings\Models\YarnType;

/**
 * @property $yarn_transfer_id
 * @property $item_description
 * @property $yarn_count_id
 * @property $yarn_composition_id
 * @property $yarn_type_id
 * @property $yarn_color
 * @property $yarn_lot
 * @property $yarn_brand
 * @property $uom_id
 * @property $transfer_qty
 * @property $rate
 * @property $transfer_value
 * @property $store_id
 * @property $floor_id
 * @property $room_id
 * @property $rack_id
 * @property $shelf_id
 * @property $bin_id
 * @property $remarks
 */
class YarnTransferDetail extends Model
{
    use SoftDeletes, ModelCommonTrait;
    protected $table = 'yarn_transfer_details';

    protected $fillable = [
        'yarn_transfer_id',
        'item_description',
        'yarn_count_id',
        'yarn_composition_id',
        'yarn_type_id',
        'yarn_color',
        'yarn_lot',
        'yarn_brand',
        'uom_id',
        'transfer_qty',
        'rate',
        'transfer_value',
        'store_id',
        'floor_id',
        'room_id',
        'rack_id',
        'shelf_id',
        'bin_id',
        'remarks',
        'created_by',
        'updated_by',
        'deleted_by',
        'deleted_at',
    ];

    public function setTransferValueAttribute($value)
    {
        $this->attributes['transfer_value'] = $this->transfer_qty * $this->rate;
    }

    public function yarnTransfer(): BelongsTo
    {
        return $this->belongsTo(YarnTransfer::class, 'yarn_transfer_id')->withDefault();
    }

    public function uom(): BelongsTo
    {
        return $this->belongsTo(UnitOfMeasurement::class, 'uom_id')->withDefault();
    }

    public function store(): BelongsTo
    {
        return $this->belongsTo(Store::class, 'store_id')->withDefault();
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

    public function bin(): BelongsTo
    {
        return $this->belongsTo(StoreBin::class, 'bin_id')->withDefault();
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


    public function transfer(): BelongsTo
    {
        return $this->belongsTo(YarnTransfer::class, 'yarn_transfer_id')->withDefault();
    }
}
