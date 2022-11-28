<?php

namespace SkylarkSoft\GoRMG\Knitting\Models;

use App\ModelCommonTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use SkylarkSoft\GoRMG\SystemSettings\Models\Factory;

class KnittingRollDeliveryChallanDetail extends Model
{
    use SoftDeletes, ModelCommonTrait;

    protected $table = "knitting_roll_delivery_challan_details";

    protected $fillable = [
        'challan_no',
        'plan_info_id',
        'knit_card_id',
        'knitting_program_id',
        'knitting_program_roll_id',
        'challan_status',
        'factory_id',
        'received_status',
        'created_by',
        'updated_by',
        'deleted_by'
    ];

    public function challan(): BelongsTo
    {
        return $this->belongsTo(KnittingRollDeliveryChallan::class, 'challan_no', 'challan_no')->withDefault();
    }

    public function planningInfo(): BelongsTo
    {
        return $this->belongsTo(PlanningInfo::class, 'plan_info_id', 'id')->withDefault();
    }

    public function knittingProgram(): BelongsTo
    {
        return $this->belongsTo(KnittingProgram::class, 'knitting_program_id', 'id')->withDefault();
    }

    public function knitCard(): BelongsTo
    {
        return $this->belongsTo(KnitCard::class, 'knit_card_id', 'id')->withDefault();
    }

    public function knitProgramRoll(): BelongsTo
    {
        return $this->belongsTo(KnitProgramRoll::class, 'knitting_program_roll_id', 'id')->withDefault();
    }

    public function factory(): BelongsTo
    {
        return $this->belongsTo(Factory::class, 'factory_id')->withDefault();
    }
}
