<?php

namespace SkylarkSoft\GoRMG\Merchandising\Models\PurchaseRequisitions;

use App\Casts\Json;
use App\Constants\ApplicationConstant;
use App\Helpers\UniqueCodeGenerator;
use App\Models\BelongsToDealingMerchant;
use App\Models\BelongsToFactory;
use App\Models\CommonModelTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class YarnPurchaseRequisition extends Model
{
    use SoftDeletes;
    use BelongsToFactory;
    use BelongsToDealingMerchant;
    use CommonModelTrait;

    protected $table = 'yarn_purchase_requisitions';


    protected $fillable = [
        'requisition_no',
        'factory_id',
        'required_date',
        'requisition_date',
        'pay_mode',
        'source',
        'currency',
        'dealing_merchant_id',
        'attention',
        'remarks',
        'ready_to_approve',
        'unapproved_request',
        'terms_condition',
        'is_approved',
        'created_by',
        'updated_by',
        'deleted_by',
    ];
    protected $appends = [
        'pay_mode_value',
        'source_value',
    ];
    protected $casts = [
        'terms_condition' => Json::class,
    ];

    public function getPayModeValueAttribute(): ?string
    {
        if ($this->attributes['pay_mode'] === 0) {
            return null;
        }

        return ApplicationConstant::PAY_MODES[$this->attributes['pay_mode']];
    }

    public function getSourceValueAttribute(): ?string
    {
        if ($this->attributes['source'] === 0) {
            return null;
        }

        return ApplicationConstant::SOURCES[$this->attributes['source']];
    }

    public function approve()
    {
        $this->attributes['is_approved'] = 1;
        $this->save();
    }

    protected static function boot()
    {
        parent::boot();

        self::created(function ($model) {
            $model->requisition_no = UniqueCodeGenerator::generate('YPR', $model->id);
            $model->save();
        });
    }

    public function details(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(YarnPurchaseRequisitionDetails::class, 'requisition_id');
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
                return $q->whereYear('requisition_date', $request->get('year'));
            })
            ->when($request->get('booking_no'), function ($q) use ($request) {
                return $q->where('requisition_no', $request->get('booking_no'));
            })
            ->when($request->get('style'), function ($query) use ($request) {
                return $query->whereHas('bookingDetails', function ($innerQuery) use ($request) {
                    return $innerQuery->where('style_name', $request->get('style'));
                });
            })
            ->when($request->get('fromDate') && $request->get('toDate'), function ($q) use ($request) {
                return $q->whereBetween('requisition_date', [$request->get('fromDate'), $request->get('toDate')]);
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

}
