<?php

namespace SkylarkSoft\GoRMG\Knitting\Models;

use App\ModelCommonTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use SkylarkSoft\GoRMG\Inventory\YarnItemAction;
use SkylarkSoft\GoRMG\Knitting\Filters\Filter;
use SkylarkSoft\GoRMG\SystemSettings\Models\Buyer;
use SkylarkSoft\GoRMG\SystemSettings\Models\Color;
use SkylarkSoft\GoRMG\SystemSettings\Models\CompositionType;
use SkylarkSoft\GoRMG\SystemSettings\Models\Factory;
use SkylarkSoft\GoRMG\SystemSettings\Models\Supplier;
use SkylarkSoft\GoRMG\SystemSettings\Models\YarnComposition;
use SkylarkSoft\GoRMG\SystemSettings\Models\YarnCount;
use SkylarkSoft\GoRMG\SystemSettings\Models\YarnType;

class YarnAllocationDetail extends Model
{
    use SoftDeletes, ModelCommonTrait;

    protected $fillable = [
        'yarn_allocation_id',
        'yarn_description',
        'yarn_count_id',
        'yarn_composition_id',
        'yarn_type_id',
        'yarn_color',
        'yarn_brand',
        'knitting_program_id',
        'knitting_program_color_id',
        'supplier_id',
        'yarn_lot',
        'store_id',
        'uom_id',
        'allocated_qty',
        'created_by',
        'updated_by',
        'deleted_by',
    ];

    protected $appends=[
        'yarn_requisition', 'previous_total_yarn_requisition_qty'
    ];

    public function supplier(): BelongsTo
    {
        return $this->belongsTo(Supplier::class, 'supplier_id')->select('id', 'name')->withDefault();
    }

    public function yarnAllocation(): BelongsTo
    {
        return $this->belongsTo(YarnAllocation::class, 'yarn_allocation_id')->withDefault();
    }


    public function yarn_composition(): BelongsTo
    {
        return $this->belongsTo(YarnComposition::class, 'yarn_composition_id')->withDefault();
    }

    public function yarn_count(): BelongsTo
    {
        return $this->belongsTo(YarnCount::class, 'yarn_count_id')->withDefault();
    }

    public function yarn_type(): BelongsTo
    {
        return $this->belongsTo(CompositionType::class, 'yarn_type_id')->withDefault();
    }

    public function program(): BelongsTo
    {
        return $this->belongsTo(KnittingProgram::class, 'knitting_program_id')->withDefault();
    }

    private function getRequisitionId($reqNo) {
        return YarnRequisition::query()
            ->where('requisition_no', $reqNo)
            ->first()->id ?? null;
    }

    public function getYarnRequisitionAttribute()
    {
        return YarnRequisitionDetail::query()
            ->where('yarn_requisition_id', $this->getRequisitionId(request()->get('req_no')))
            ->where('knitting_program_color_id', $this->attributes['knitting_program_color_id'])
            ->where(YarnItemAction::itemCriteria($this->attributes))
            ->first();
    }

    public function getPreviousTotalYarnRequisitionQtyAttribute()
    {
        return YarnRequisitionDetail::query()
            ->whereHas('yarnRequisition', Filter::applyFilter('program_id', $this->attributes['knitting_program_id']))
            ->where('knitting_program_color_id', $this->attributes['knitting_program_color_id'])
            ->where(YarnItemAction::itemCriteria($this->attributes))
            ->sum('requisition_qty');
    }

    public function programColor(): BelongsTo
    {
        return $this->belongsTo(KnittingProgramColorsQty::class, 'knitting_program_color_id')->withDefault();
    }
}
