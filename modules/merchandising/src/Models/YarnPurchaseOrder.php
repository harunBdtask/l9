<?php

namespace SkylarkSoft\GoRMG\Merchandising\Models;

use App\Constants\ApplicationConstant;
use App\Helpers\UniqueCodeGenerator;
use App\Models\BelongsToFactory;
use App\Models\CommonModelTrait;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use SkylarkSoft\GoRMG\SystemSettings\Models\Buyer;
use SkylarkSoft\GoRMG\SystemSettings\Models\Supplier;
use SkylarkSoft\GoRMG\SystemSettings\Models\UnitOfMeasurement;
use SkylarkSoft\GoRMG\SystemSettings\Models\User;
use SkylarkSoft\GoRMG\SystemSettings\Models\YarnComposition;
use SkylarkSoft\GoRMG\SystemSettings\Models\YarnCount;

class YarnPurchaseOrder extends Model
{
    use SoftDeletes;
    use BelongsToFactory;
    use CommonModelTrait;

    protected $table = 'yarn_purchase_orders';

    protected $fillable = [
        'wo_no',
        'factory_id',
        'buyer_id',
        'supplier_id',
        'wo_date',
        'delivery_date',
        'pay_mode',
        'source',
        'currency',
        'wo_basis',
        'pay_term',
        'incoterm_id',
        'tenor',
        'attention',
        'remarks',
        'garment_production_schedule',
        'order_note',
        'created_by',
        'updated_by',
        'deleted_by',
        'is_approved',
        'ready_to_approve',
        'unapproved_request',
        'step',
        'rework_status',
        'cancel_status',
    ];
    protected $appends = [
        'pay_mode_value',
        'source_value',
        'budget_job_no',
        'year',
    ];

    public function getYearAttribute(): ?int
    {
        if (!$this->wo_date) {
            return null;
        }
        return Carbon::parse($this->wo_date)->year;
    }

    public function getPayModeValueAttribute(): ?string
    {
        if ($this->attributes['pay_mode'] === 0 || $this->attributes['pay_mode'] === null) {
            return null;
        }

        return array_key_exists($this->attributes['pay_mode'], ApplicationConstant::PAY_MODES) ? ApplicationConstant::PAY_MODES[$this->attributes['pay_mode']] : null;

    }

    public function getSourceValueAttribute(): ?string
    {
        if ($this->attributes['source'] === 0 || $this->attributes['source'] === null) {
            return null;
        }

        return array_key_exists($this->attributes['source'], ApplicationConstant::SOURCES) ? ApplicationConstant::SOURCES[$this->attributes['source']] : null;
    }

    public function getWoBasicValueAttribute(): ?string
    {
        if (isset($this->attributes['wo_basis'])) {
            return null;
        }

        return array_key_exists($this->attributes['wo_basis'], ApplicationConstant::WO_BASIC) ? ApplicationConstant::WO_BASIC[$this->attributes['wo_basis']] : null;
    }

    protected static function boot()
    {
        parent::boot();

        self::created(function ($model) {
            $model->wo_no = UniqueCodeGenerator::generate('YPO', $model->id);
            $model->save();
        });
    }

    public function details(): HasMany
    {
        return $this->hasMany(YarnPurchaseOrderDetail::class, 'yarn_purchase_order_id');
    }

    public function supplier(): BelongsTo
    {
        return $this->belongsTo(Supplier::class, 'supplier_id')->withDefault();
    }

    public function buyer(): BelongsTo
    {
        return $this->belongsTo(Buyer::class, 'buyer_id');
    }

    public function uom(): BelongsTo
    {
        return $this->belongsTo(UnitOfMeasurement::class, 'uom_id');
    }

    public function yarnCount(): BelongsTo
    {
        return $this->belongsTo(YarnCount::class, 'yarn_count_id');
    }

    public function yarnComposition(): BelongsTo
    {
        return $this->belongsTo(YarnComposition::class, 'yarn_composition_id');
    }

    public function getBudgetJobNoAttribute($value)
    {
        return $this->details()->pluck('unique_id')->unique()->implode(',');
    }

    public function scopeApprovalFilter($query, $request, $previousStep, $step)
    {
        return $query->when($request->get('factory'), function ($q) use ($request) {
            return $q->where('factory_id', $request->get('factory'));
        })
            ->when($request->get('buyer'), function ($q) use ($request) {
                return $q->where('dealing_merchant_id', $request->get('buyer'));
            })
            ->when($request->get('year'), function ($q) use ($request) {
                return $q->whereYear('wo_date', $request->get('year'));
            })
            ->when($request->get('booking_no'), function ($q) use ($request) {
                return $q->where('wo_no', $request->get('booking_no'));
            })
            ->when($request->get('style'), function ($query) use ($request) {
                return $query->whereHas('bookingDetails', function ($innerQuery) use ($request) {
                    return $innerQuery->where('style_name', $request->get('style'));
                });
            })
            ->when($request->get('fromDate') && $request->get('toDate'), function ($q) use ($request) {
                return $q->whereBetween('wo_date', [$request->get('fromDate'), $request->get('toDate')]);
            })
            ->when($request->get('approvalType'), function ($query) use ($request, $previousStep, $step) {
                $query->when($request->get('approvalType') == 1, function ($query) use ($request, $previousStep) {
                    $query->where('ready_to_approve', $request->get('approvalType'))
                        ->where('is_approved', '=', 0)
                        ->where('step', $previousStep);
                })->when($request->get('approvalType') == 2, function ($query) use ($step) {
                    $query->where('step', $step);
                });
            });
    }

    public function createdBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by')->withDefault();
    }
}
