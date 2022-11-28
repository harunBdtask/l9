<?php

namespace SkylarkSoft\GoRMG\Knitting\Models;

use App\Models\CommonModelTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use SkylarkSoft\GoRMG\SystemSettings\Models\Buyer;
use SkylarkSoft\GoRMG\SystemSettings\Models\Factory;
use SkylarkSoft\GoRMG\SystemSettings\Models\Machine;
use SkylarkSoft\GoRMG\SystemSettings\Models\User;

class KnitCard extends Model
{
    /**
     *  * @property $machine_allocation_status
     *  * @property $current_production_status
     *  * @property $current_machine_priority
     *  * @property $current_production_remarks
     */
    use HasFactory, SoftDeletes, CommonModelTrait;

    protected $fillable = [
        'factory_id',
        'buyer_id',
        'plan_info_id',
        'knitting_program_id',
        'current_machine_id',
        'knit_card_no',
        'sales_order_no',
        'booking_date',
        'fabric_type',
        'season_id',
        'color_id',
        'color',
        'gsm',
        'assign_qty',
        'production_target_qty',
        'knit_card_date',
        'program_dia',
        'program_gg',
        'remarks',
        'machine_allocation_status',
        'current_production_status',
        'current_machine_priority',
        'current_production_remarks',
        'qc_pass_qty',
        'qc_pending_status',
        'created_by',
        'updated_by',
        'deleted_by',
    ];

    const MACHINE_STATUS = [
        0 => 'Unallocated',
        1 => 'Allocated'
    ];

    const MACHINE_ALLOCATED = 1;

    const PROD_STATUS = [
        0 => 'N/A',
        1 => 'On Queue',
        2 => 'Running',
        3 => 'Stopped',
        4 => 'Cancelled',
        5 => 'Completed'
    ];

    public static function booted()
    {
        static::created(function ($model) {
            $model->knit_card_no = str_pad($model->id, 10, '0', STR_PAD_LEFT);
            $model->save();
        });
    }

    public function factory(): BelongsTo
    {
        return $this->belongsTo(Factory::class)->withDefault();
    }

    public function buyer(): BelongsTo
    {
        return $this->belongsTo(Buyer::class)->withDefault();
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by')->withDefault();
    }

    public function planInfo(): BelongsTo
    {
        return $this->belongsTo(PlanningInfo::class, 'plan_info_id', 'id')->withDefault();
    }

    public function machine(): BelongsTo
    {
        return $this->belongsTo(Machine::class, 'current_machine_id', 'id')->withDefault();
    }

    public function program(): BelongsTo
    {
        return $this->belongsTo(KnittingProgram::class, 'knitting_program_id', 'id')->withDefault();
    }

    public function yarnDetails(): HasMany
    {
        return $this->hasMany(KnitCardYarnDetail::class);
    }

    public function knitCardRoll(): HasMany
    {
        return $this->hasMany(KnitProgramRoll::class);
    }

    public function knitCardRollWithDelivered(): HasMany
    {
        return $this->hasMany(KnitProgramRoll::class)->where('delivery_status', 1);
    }

    public function knitCardRollWithoutDelivered(): HasMany
    {
        return $this->hasMany(KnitProgramRoll::class)->where('delivery_status', 0);
    }
}
