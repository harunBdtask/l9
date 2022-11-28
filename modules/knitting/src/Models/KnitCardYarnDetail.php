<?php

namespace SkylarkSoft\GoRMG\Knitting\Models;

use App\Models\CommonModelTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use SkylarkSoft\GoRMG\SystemSettings\Models\CompositionType;
use SkylarkSoft\GoRMG\SystemSettings\Models\YarnComposition;
use SkylarkSoft\GoRMG\SystemSettings\Models\YarnCount;
use SkylarkSoft\GoRMG\SystemSettings\Models\YarnType;

class KnitCardYarnDetail extends Model
{
    use HasFactory, SoftDeletes, CommonModelTrait;

    protected $fillable = [
        'factory_id',
        'plan_info_id',
        'knitting_program_id',
        'knit_card_id',
        'knit_yarn_allocation_detail_id',
        'yarn_count_id',
        'yarn_composition_id',
        'yarn_color',
        'yarn_brand',
        'yarn_lot',
        'yarn_type_id',
        'store_id',
        'uom_id',
        'vdq',
        'created_by',
        'updated_by',
        'deleted_by',
    ];


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
}
