<?php

namespace SkylarkSoft\GoRMG\Knitting\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use PhpParser\Node\Expr\Cast\Double;
use SkylarkSoft\GoRMG\SystemSettings\Models\Color;
use SkylarkSoft\GoRMG\SystemSettings\Models\CompositionType;
use SkylarkSoft\GoRMG\SystemSettings\Models\Supplier;
use SkylarkSoft\GoRMG\SystemSettings\Models\YarnComposition;
use SkylarkSoft\GoRMG\SystemSettings\Models\YarnCount;
use SkylarkSoft\GoRMG\SystemSettings\Models\YarnType;

class YarnRequisitionDetail extends Model
{
    use HasFactory;

    protected $fillable = [
        'yarn_requisition_id',
        'knitting_program_color_id',
        'supplier_id',
        'yarn_type_id',
        'yarn_count_id',
        'yarn_composition_id',
        'yarn_lot',
        'yarn_brand',
        'requisition_qty',
        'requisition_date',
        'remarks',
        'store_id',
        'uom_id',
        'yarn_color',
    ];

    public function getRequisitionQtyAttribute()
    {
        return (double) $this->attributes['requisition_qty'];
    }

    public function supplier(): BelongsTo
    {
        return $this->belongsTo(Supplier::class, 'supplier_id')->withDefault();
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

    public function yarnRequisition(): BelongsTo
    {
        return $this->belongsTo(YarnRequisition::class, 'yarn_requisition_id')->withDefault();
    }

    public function knittingProgramColor(): BelongsTo
    {
        return $this->belongsTo(KnittingProgramColorsQty::class, 'knitting_program_color_id');
    }
}
