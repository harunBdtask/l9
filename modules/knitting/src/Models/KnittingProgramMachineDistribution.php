<?php

namespace SkylarkSoft\GoRMG\Knitting\Models;

use App\ModelCommonTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use SkylarkSoft\GoRMG\SystemSettings\Models\Machine;

class KnittingProgramMachineDistribution extends Model
{
    use SoftDeletes, ModelCommonTrait;

    protected $table = 'knitting_program_machine_distributions';

    protected $fillable = [
        'plan_info_id',
        'knitting_program_id',
        'machine_id',
        'distribution_qty',
        'factory_id',
        'created_by',
        'updated_by',
        'deleted_by',
    ];

    protected $dates = ['deleted_at'];

    public function planningInfo(): BelongsTo
    {
        return $this->belongsTo(PlanningInfo::class, 'plan_info_id', 'id')->withDefault();
    }

    public function knittingProgram(): BelongsTo
    {
        return $this->belongsTo(KnittingProgram::class, 'knitting_program_id', 'id')->withDefault();
    }

    public function machine(): BelongsTo
    {
        return $this->belongsTo(Machine::class, 'machine_id', 'id')->withDefault();
    }
}
