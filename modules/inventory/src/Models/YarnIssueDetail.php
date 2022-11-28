<?php

namespace SkylarkSoft\GoRMG\Inventory\Models;

use App\ModelCommonTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use SkylarkSoft\GoRMG\Knitting\Models\YarnRequisition;
use SkylarkSoft\GoRMG\Merchandising\Models\Fabric_composition;
use SkylarkSoft\GoRMG\SystemSettings\Models\CompositionType;
use SkylarkSoft\GoRMG\SystemSettings\Models\Factory;
use SkylarkSoft\GoRMG\SystemSettings\Models\Supplier;
use SkylarkSoft\GoRMG\SystemSettings\Models\UnitOfMeasurement;
use SkylarkSoft\GoRMG\SystemSettings\Models\YarnComposition;
use SkylarkSoft\GoRMG\SystemSettings\Models\YarnCount;
use SkylarkSoft\GoRMG\SystemSettings\Models\YarnType;

/**
 * @property $yarn_issue_id
 * @property $demand_no
 * @property $yarn_lot
 * @property $issue_qty
 * @property $rate
 * @property $issue_value
 * @property $returnable_qty
 * @property $uom_id
 * @property $yarn_count_id
 * @property $yarn_composition_id
 * @property $yarn_type_id
 * @property $yarn_color
 * @property $dyeing_color
 * @property $store_id
 * @property $floor_id
 * @property $room_id
 * @property $rack_id
 * @property $shelf_id
 * @property $bin_id
 * @property $no_of_bag
 * @property $no_of_cone_per_bag
 * @property $no_of_cone
 * @property $weight_per_bag
 * @property $weight_per_cone
 */
class YarnIssueDetail extends Model
{
    use SoftDeletes, ModelCommonTrait;

    public function setIssueValueAttribute($value)
    {
        $this->attributes['issue_value'] = $this->issue_qty * $this->rate;
    }

    protected $fillable = [
        'rate',
        'uom_id',
        'remarks',
        'yarn_lot',
        'room_id',
        'bin_id',
        'store_id',
        'rack_id',
        'floor_id',
        'shelf_id',
        'no_of_bag',
        'demand_no',
        'booking_type',
        'requisition_color_id',
        'issue_qty',
        'no_of_cone',
        'created_by',
        'yarn_brand',
        'yarn_color',
        'deleted_by',
        'updated_by',
        'deleted_at',
        'issue_value',
        'dyeing_color',
        'yarn_type_id',
        'yarn_issue_id',
        'yarn_count_id',
        'weight_per_bag',
        'returnable_qty',
        'weight_per_cone',
        'yarn_composition_id',
        'no_of_cone_per_bag',
    ];

    public function issue(): BelongsTo
    {
        return $this->belongsTo(YarnIssue::class, 'yarn_issue_id')->withDefault();
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

    public function requisition(): BelongsTo
    {
        return $this->belongsTo(YarnRequisition::class, 'demand_no', 'requisition_no')->withDefault();
    }
}
