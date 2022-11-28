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
 * @property $yarn_count_id
 * @property $yarn_composition_id
 * @property $yarn_type_id
 * @property $yarn_color
 * @property $yarn_lot
 * @property $uom_id
 * @property $return_qty
 * @property $rate
 * @property $return_value
 * @property $receiveReturn
 * @property $store_id
 */

class YarnReceiveReturnDetail extends Model
{
    use SoftDeletes, ModelCommonTrait;
    protected $table = 'yarn_receive_return_details';

    protected $fillable = [
        'receive_return_id',
        'uom_id',
        'yarn_count_id',
        'yarn_composition_id',
        'yarn_type_id',
        'yarn_lot',
        'yarn_color',
        'yarn_brand',
        'return_qty',
        'return_value',
        'rate',
        'store_id',
        'floor_id',
        'room_id',
        'rack_id',
        'shelf_id',
        'bin_id',
        'created_by',
        'updated_by',
        'deleted_by'
    ];

    public function receiveReturn(): BelongsTo
    {
        return $this->belongsTo(YarnReceiveReturn::class, 'receive_return_id')->withDefault();
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
}
