<?php

namespace SkylarkSoft\GoRMG\Sample\Models;

use App\Casts\Json;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use SkylarkSoft\GoRMG\SystemSettings\Models\Buyer;
use SkylarkSoft\GoRMG\SystemSettings\Models\BuyingAgentModel;
use SkylarkSoft\GoRMG\SystemSettings\Models\Currency;
use SkylarkSoft\GoRMG\SystemSettings\Models\Factory;
use SkylarkSoft\GoRMG\SystemSettings\Models\ProductDepartments;
use SkylarkSoft\GoRMG\SystemSettings\Models\Season;
use SkylarkSoft\GoRMG\SystemSettings\Models\User;

class SampleOrderRequisition extends Model
{
    use SoftDeletes;

    protected $table = 'sample_order_requisitions';

    const AFTER_ORDER = 'after_order';
    const BEFORE_ORDER = 'before_order';
    const RND = 'r&d';
    const SAMPLE_STAGES = [
        'after_order' => 'After Order',
        'before_order' => 'Before Order',
        'rnd' => 'R&D',
    ];
    const YESNO = [
        '1' => 'Yes',
        '2' => 'No',
    ];

    protected $fillable = [
        'requisition_id',
        'sample_stage',
        'req_date',
        'style_name',
        'repeat_style_name',
        'factory_id',
        'location',
        'buyer_id',
        'season_id',
        'dealing_merchant_id',
        'bh_merchant_id',
        'product_department_id',
        'buyer_ref',
        'agent_name',
        'est_ship_date',
        'delivery_date',
        'remarks',
        'file',
        'currency',
        'ready_for_approve',
        'team_leader_id',
        'lab_test',
        'booking_no',
        'control_ref_no',
        'ref_no',
        'created_by',
        'updated_by',
        'deleted_by',
        'requis_details_cal',
        'fabric_details_cal',
        'accessories_details_cal',
    ];

    protected $appends = ['stage', 'year', 'lab_test_text'];

    protected $casts = [
        'requis_details_cal' => Json::class,
        'fabric_details_cal' => Json::class,
        'accessories_details_cal' => Json::class,
    ];

    public function getStageAttribute(): string
    {
        return self::SAMPLE_STAGES[$this->attributes['sample_stage']] ?: '';
    }

    public function getLabTestTextAttribute(): string
    {
        if (isset($this->attributes['lab_test']) && $this->attributes['lab_test'] != 0) {
            return self::YESNO[$this->attributes['lab_test']];
        }

        return '';
    }

    protected static function boot()
    {
        parent::boot();

        self::created(function ($model) {
            $model->requisition_id = 'UGL-SRE-' . date('y') . '-' . str_pad($model->id, 6, '0', STR_PAD_LEFT);
            $model->save();
        });

        self::creating(function ($model) {
            $model->created_by = Auth::id();
        });

        self::updating(function ($model) {
            $model->updated_by = Auth::id();
        });

        self::deleted(function ($model) {
            if (in_array('deleted_by', $model->getFillable())) {
                DB::table($model->table)->where('id', $model->id)->update([
                    'deleted_by' => Auth::id(),
                ]);
            }
        });
    }

    /**
     * @return BelongsTo
     */
    public function factory(): BelongsTo
    {
        return $this->belongsTo(Factory::class)->withDefault();
    }

    public function buyer(): BelongsTo
    {
        return $this->belongsTo(Buyer::class)->withDefault();
    }

    public function department(): BelongsTo
    {
        return $this->belongsTo(ProductDepartments::class, 'product_department_id')->withDefault();
    }

    public function merchant(): BelongsTo
    {
        return $this->belongsTo(User::class, 'dealing_merchant_id')->withDefault();
    }

    public function details(): HasMany
    {
        return $this->hasMany(SampleOrderRequisitionDetails::class, 'sample_order_requisition_id');
    }

    public function fabrics(): HasMany
    {
        return $this->hasMany(SampleOrderFabric::class, 'sample_order_requisition_id');
    }

    public function fabricDetails(): HasMany
    {
        return $this->hasMany(SampleOrderFabricDetails::class, 'sample_order_requisition_id');
    }

    public function accessories(): HasMany
    {
        return $this->hasMany(SampleOrderAccessoriesDetails::class, 'sample_order_requisition_id');
    }

    public function season(): BelongsTo
    {
        return $this->belongsTo(Season::class, 'season_id')->withDefault();
    }

    public function dealingMerchant(): BelongsTo
    {
        return $this->belongsTo(User::class, 'dealing_merchant_id')->withDefault();
    }

    public function teamLeader(): BelongsTo
    {
        return $this->belongsTo(User::class, 'team_leader_id')->withDefault();
    }

    public function currencyName(): BelongsTo
    {
        return $this->belongsTo(Currency::class, 'currency')->withDefault();
    }

    public function BuyingAgentName(): BelongsTo
    {
        return $this->belongsTo(BuyingAgentModel::class, 'bh_merchant_id')->withDefault();
    }

    public function getYearAttribute()
    {
        return $this->req_date ? Date('Y', strtotime($this->req_date)) : null;
    }
}
