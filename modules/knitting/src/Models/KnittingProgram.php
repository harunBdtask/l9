<?php

namespace SkylarkSoft\GoRMG\Knitting\Models;

use App\Casts\Json;
use App\ModelCommonTrait;
use Dyrynda\Database\Support\CascadeSoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use SkylarkSoft\GoRMG\SystemSettings\Models\Buyer;
use SkylarkSoft\GoRMG\SystemSettings\Models\ColorRange;
use SkylarkSoft\GoRMG\SystemSettings\Models\Factory;
use SkylarkSoft\GoRMG\SystemSettings\Models\Supplier;

class KnittingProgram extends Model
{
    use ModelCommonTrait, SoftDeletes;

    protected $table = 'knitting_programs';

    protected $fillable = [
        'plan_info_id',
        'booking_no',
        'booking_type',
        'program_no',
        'knitting_source_id',
        'color_range_id',
        'feeder_id',
        'knitting_party_id',
        'knitting_party_type', // Factory or Supplier
        'machine_nos',
        'fabric_description',
        'fabric_colors',
        'finish_fabric_dia',
        'machines_capacity',
        'stitch_length',
        'machine_dia',
        'machine_gg',
        'machine_type_info',
        'knitting_charge',
        'remarks',
        'program_date',
        'start_date',
        'end_date',
        'program_qty',
        'production_qty',
        'qc_pass_qty',
        'production_pending_status',
        'qc_pending_status',
        'status',
        'fleece_info',
        'requisition_no',
        'factory_id',
        'buyer_id',
        'created_by',
        'updated_by',
        'deleted_by',
    ];

    protected $dates = ['deleted_at'];

    const KnittingPartyModels = [
        'Factory' => Factory::class,
        'Supplier' => Supplier::class,
    ];

    const KnittingSources = [
        '1' => "Inhouse",
        '2' => "Subcontract",
    ];

    protected $casts = [
        'fabric_colors' => Json::class,
        'fleece_info' => Json::class,
        'machine_nos' => Json::class,
    ];

    protected $cascadeDeletes = [
        'knitProgramRolls',
        'knittingProgramColorsQtys',
        'knittingProgramCollarCuffs',
        'knittingRollDeliveryChallanDetails',
        'knittingProgramMachineDistributions',
        'knittingProgramCollarCuffProductions',
        'knittingProgramStripeDetails',
    ];

    protected $appends = [
        'party_name',
        'feeder_text',
        'knitting_source_value'
    ];

    protected $feeders = [
        1 => 'Half Feeder',
        2 => 'Full Feeder',
    ];

    const PLANNING_STATUS = [
        'waiting' => 'Waiting',
        'running' => 'Running',
        'stop' => 'Stop/Close',
    ];

    public static function booted()
    {
        static::created(function ($model) {
            $model->program_no = str_pad($model->id, 10, '0', STR_PAD_LEFT);
            $model->save();
        });
    }

    public function getPartyNameAttribute()
    {
        $relationShipClass = self::KnittingPartyModels[$this->knitting_party_type ?? 'Factory'];
        $query = $relationShipClass::where('id', $this->knitting_party_id)->first();
        return $query ? ($this->knitting_party_type == 'Factory' ? $query->factory_name : $query->name) : null;
    }

    public function getFeederTextAttribute(): string
    {
        return isset($this->attributes['feeder_id']) ? $this->feeders[$this->attributes['feeder_id']] : '';
    }

    public function machines(): HasMany
    {
        return $this->hasMany(KnittingProgramMachineDistribution::class, 'knitting_program_id');
    }

    public function collarCuffs(): HasMany
    {
        return $this->hasMany(KnittingProgramCollarCuff::class, 'knitting_program_id');
    }

    public function stripeDetails(): BelongsTo
    {
        return $this->belongsTo(KnittingProgramStripeDetail::class, 'knitting_program_id');
    }

    public function planInfo(): BelongsTo
    {
        return $this->belongsTo(PlanningInfo::class, 'plan_info_id', 'id')->withDefault();
    }

    public function colorRange(): BelongsTo
    {
        return $this->belongsTo(ColorRange::class, 'color_range_id', 'id')->withDefault();
    }

    public function knittingParty(): BelongsTo
    {
        $relationShipClass = self::KnittingPartyModels[$this->knitting_party_type ?? 'Factory'];
        return $this->belongsTo($relationShipClass, 'knitting_party_id', 'id')->withDefault();
    }

    public function knitting_program_colors_qtys(): HasMany
    {
        return $this->hasMany(KnittingProgramColorsQty::class, 'knitting_program_id', 'id');
    }

    public function knittingProgramMachineDistributions(): HasMany
    {
        return $this->hasMany(KnittingProgramMachineDistribution::class, 'knitting_program_id', 'id');
    }

    public function knittingProgramStripeDetails(): HasMany
    {
        return $this->hasMany(KnittingProgramStripeDetail::class, 'knitting_program_id', 'id');
    }

    public function knittingProgramCollarCuffs(): HasMany
    {
        return $this->hasMany(KnittingProgramCollarCuff::class, 'knitting_program_id', 'id');
    }

    public function knittingProgramCollarCuffProductions(): HasMany
    {
        return $this->hasMany(KnittingProgramCollarCuffProduction::class, 'knitting_program_id', 'id');
    }

    public function knitProgramRolls(): HasMany
    {
        return $this->hasMany(KnitProgramRoll::class, 'knitting_program_id', 'id');
    }

    public function knittingRollDeliveryChallanDetails(): HasMany
    {
        return $this->hasMany(KnittingRollDeliveryChallanDetail::class, 'knitting_program_id', 'id');
    }

    public function getKnittingSourceValueAttribute(): string
    {
        return array_key_exists($this->knitting_source_id, self::KnittingSources) ? self::KnittingSources[$this->knitting_source_id] : '';
    }

    public function yarnRequisition(): HasMany
    {
        return $this->hasMany(YarnRequisition::class, 'program_id', 'id');
    }

    public function yarnAllocation(): HasMany
    {
        return $this->hasMany(YarnAllocationDetail::class,'knitting_program_id','id');
    }

    public function knitCard(): HasMany
    {
        return $this->hasMany(KnitCard::class,'knitting_program_id');
    }

    public function buyer(): BelongsTo
    {
        return $this->belongsTo(Buyer::class)->withDefault();
    }
}
