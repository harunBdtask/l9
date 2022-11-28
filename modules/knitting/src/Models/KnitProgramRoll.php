<?php

namespace SkylarkSoft\GoRMG\Knitting\Models;

use App\Casts\Json;
use App\ModelCommonTrait;
use Dyrynda\Database\Support\CascadeSoftDeletes;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use SkylarkSoft\GoRMG\Merchandising\Actions\StyleAuditReportAction;
use SkylarkSoft\GoRMG\SystemSettings\Models\Factory;
use SkylarkSoft\GoRMG\SystemSettings\Models\Operator;
use SkylarkSoft\GoRMG\SystemSettings\Models\Shift;

/**
 * Program wise roll assign changed to Knit Card wise Roll assign.
 * Now knit_program_rolls table use for knit_card_rolls.
 *
 * Don't be confused :D
 */
class KnitProgramRoll extends Model
{
    use SoftDeletes, ModelCommonTrait, CascadeSoftDeletes;

    protected $table = 'knit_program_rolls';

    protected $fillable = [
        'plan_info_id',
        'knitting_program_id',
        'knit_card_id',
        'point_calculation_method',
        'shift_id',
        'operator_id',
        'lot_no',
        'roll_weight',
        'production_datetime',
        'production_pcs_total',
        'qc_datetime',
        'qc_roll_weight',
        'qc_shift_id',
        'qc_operator_id',
        'qc_fabric_dia',
        'qc_length_in_yards',
        'qc_length_in_mm',
        'qc_fabric_gsm',
        'qc_fault_details',
        'qc_total_point',
        'qc_grade_point',
        'qc_fabric_grade',
        'qc_status',
        'reject_roll_weight',
        'delivery_status',
        'delivery_challan_no',
        'factory_id',
        'created_by',
        'updated_by',
        'deleted_by'
    ];

    protected $dates = ['deleted_at'];

    protected $casts = [
        'qc_fault_details' => Json::class
    ];

    protected $appends = [
        'barcode_no'
    ];

    protected $cascadeDeletes = [
        'knittingRollDeliveryChallanDetails',
        'knittingProgramCollarCuffProductions',
    ];

    public function getBarcodeNoAttribute(): string
    {
        return str_pad($this->attributes['id'], 9, '0', STR_PAD_LEFT);
    }

    public function planningInfo()
    {
        return $this->belongsTo(PlanningInfo::class, 'plan_info_id', 'id')->withDefault();
    }

    public function knittingProgram()
    {
        return $this->belongsTo(KnittingProgram::class, 'knitting_program_id', 'id')->withDefault();
    }

    public function shift(): BelongsTo
    {
        return $this->belongsTo(Shift::class)->withDefault();
    }

    public function operator(): BelongsTo
    {
        return $this->belongsTo(Operator::class)->withDefault();
    }

    public function qcShift()
    {
        return $this->belongsTo(Shift::class, 'qc_shift_id', 'id')->withDefault();
    }

    public function qcOperator()
    {
        return $this->belongsTo(Operator::class, 'qc_operator_id', 'id')->withDefault();
    }

    public function knittingRollDeliveryChallanDetails()
    {
        return $this->hasMany(KnittingRollDeliveryChallanDetail::class, 'knitting_program_roll_id', 'id');
    }

    public function knittingProgramCollarCuffProductions(): HasMany
    {
        return $this->hasMany(KnittingProgramCollarCuffProduction::class, 'knitting_program_roll_id', 'id');
    }

    public function knitCard(): BelongsTo
    {
        return $this->belongsTo(KnitCard::class);
    }

    public function factory(): BelongsTo
    {
        return $this->belongsTo(Factory::class);
    }

    public static function booted()
    {
        static::created(function ($model) {
            if ($model->planningInfo && $model->planningInfo->order && $model->planningInfo->order->id) {
                (new StyleAuditReportAction())
                    ->init($model->planningInfo->order->id)
                    ->handleOrder()
                    ->handleBudget()
                    ->handleFabricBooking()
                    ->handleKnitting()
                    ->saveOrUpdate();
            }

        });

        static::updated(function ($model) {
            if ($model->planningInfo && $model->planningInfo->order && $model->planningInfo->order->id) {
                (new StyleAuditReportAction())
                    ->init($model->planningInfo->order->id)
                    ->handleOrder()
                    ->handleBudget()
                    ->handleFabricBooking()
                    ->handleKnitting()
                    ->saveOrUpdate();
            }

        });

        static::deleted(function ($model) {
            if ($model->planningInfo && $model->planningInfo->order && $model->planningInfo->order->id) {
                (new StyleAuditReportAction())
                    ->init($model->planningInfo->order->id)
                    ->handleOrder()
                    ->handleBudget()
                    ->handleFabricBooking()
                    ->handleKnitting()
                    ->saveOrUpdate();
            }

        });
    }
}
