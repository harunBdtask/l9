<?php

namespace SkylarkSoft\GoRMG\Merchandising\Models\Samples;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use SkylarkSoft\GoRMG\SystemSettings\Models\Buyer;
use SkylarkSoft\GoRMG\SystemSettings\Models\Factory;
use SkylarkSoft\GoRMG\SystemSettings\Models\ProductDepartments;
use SkylarkSoft\GoRMG\SystemSettings\Models\Season;
use SkylarkSoft\GoRMG\SystemSettings\Models\User;

class SampleRequisition extends Model
{
    protected $table = 'sample_requisitions';

    const AFTER_ORDER = 'after_order';
    const BEFORE_ORDER = 'before_order';
    const RND = 'r&d';
    const SAMPLE_STAGES = [
        'after_order' => 'After Order',
        'before_order' => 'Before Order',
        'rnd' => 'R&D',
    ];

    protected $fillable = [
        'requisition_id',
        'sample_stage',
        'req_date',
        'style_name',
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
        'internal_ref',
        'remarks',
        'file',
        'currency',
        'ready_for_approve',
        'created_by',
        'updated_by',
        'deleted_by',
    ];

    protected $appends = ['stage', 'year'];

    public function getStageAttribute(): string
    {
        return self::SAMPLE_STAGES[$this->attributes['sample_stage']] ?: '';
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
        return $this->hasMany(SampleRequisitionDetail::class, 'sample_requisition_id');
    }

    public function fabrics(): HasMany
    {
        return $this->hasMany(SampleRequisitionFabricDetail::class, 'requisition_id');
    }

    public function accessories(): HasMany
    {
        return $this->hasMany(SampleRequiredAccessory::class, 'sample_requisition_id');
    }

    public function season(): BelongsTo
    {
        return $this->belongsTo(Season::class, 'season_id')->withDefault();
    }

    public function dealingMerchant(): BelongsTo
    {
        return $this->belongsTo(User::class, 'dealing_merchant_id')->withDefault();
    }

    public function getYearAttribute()
    {
        return $this->req_date ? Date('Y', strtotime($this->req_date)) : null;
    }
}
