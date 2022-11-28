<?php

namespace SkylarkSoft\GoRMG\Knitting\Models;

use App\Casts\Json;
use App\ModelCommonTrait;
use Dyrynda\Database\Support\CascadeSoftDeletes;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use SkylarkSoft\GoRMG\Merchandising\Models\Order;
use SkylarkSoft\GoRMG\SystemSettings\Models\BodyPart;
use SkylarkSoft\GoRMG\SystemSettings\Models\ColorType;

class PlanningInfo extends Model
{
    use SoftDeletes, CascadeSoftDeletes;

    protected $table = 'planning_infos';
    public $timestamps = false;
    protected $fillable = [
        'plan_info_uid',
        'programmable_id',
        'programmable_type',
        'details_ids',
        'details',
        'total_qty',
        'knitting_program_ids',
        'program_date',
        'booking_no',
        'booking_type',
        'booking_date',
        'buyer_name',
        'buyer_id',
        'style_name',
        'unique_id',
        'po_no',
        'body_part',
        'color_type',
        'color_range',
        'gmt_color',
        'item_color',
        'fabric_description',
        'fabric_gsm',
        'fabric_dia',
        'dia_type',
        'booking_qty',
        'program_qty',
        'production_qty',
        'fabric_nature_id',
        'fabric_nature',
        'deleted_by'
    ];

    protected $dates = ['deleted_at'];

    protected $casts = [
        'details_ids' => Json::class,
        'knitting_program_ids' => Json::class,
    ];

    protected $cascadeDeletes = [
        'knittingPrograms',
        'knitProgramRolls',
        'knittingProgramColorsQtys',
        'knittingProgramMachineDistributions',
        'knittingRollDeliveryChallanDetails',
    ];

    protected $appends = ['fabric_type'];

    public static function booted()
    {
        static::created(function ($model) {
            $generate = str_pad($model->id, 5, "0", STR_PAD_LEFT);
            $model->plan_info_uid = getPrefix() . 'PLN-' . date('y') . '-' . $generate;
            $model->save();
        });
    }

    public function getFabricTypeAttribute(): string
    {
        $fabricType = '';
        if ($this->attributes['fabric_description']) {
            $fabricType = explode('[', $this->attributes['fabric_description'])[0] ?? '';
        }
        return $fabricType;
    }

    public function bodyPart(): belongsTo
    {
        return $this->belongsTo(BodyPart::class, 'body_part');
    }

    public function colorType(): belongsTo
    {
        return $this->belongsTo(ColorType::class, 'color_type');
    }

    public function programmable()
    {
        return $this->morphTo();
    }
    public function details()
    {
        return $this->hasMany(PlanningInfoDetail::class, 'planning_info_id');
    }

    public function knittingPrograms(): HasMany
    {
        return $this->hasMany(KnittingProgram::class, 'plan_info_id', 'id');
    }

    public function knittingProgramColorsQtys()
    {
        return $this->hasMany(KnittingProgramColorsQty::class, 'plan_info_id', 'id');
    }

    public function knittingProgramMachineDistributions()
    {
        return $this->hasMany(KnittingProgramMachineDistribution::class, 'plan_info_id', 'id');
    }

    public function knitProgramRolls()
    {
        return $this->hasMany(KnitProgramRoll::class, 'plan_info_id', 'id');
    }

    public function knittingRollDeliveryChallanDetails()
    {
        return $this->hasMany(KnittingRollDeliveryChallanDetail::class, 'plan_info_id', 'id');
    }

    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class, 'style_name', 'style_name');
    }
}
