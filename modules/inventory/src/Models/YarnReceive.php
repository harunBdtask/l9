<?php

namespace SkylarkSoft\GoRMG\Inventory\Models;

use App\Models\CommonModelTrait;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use SkylarkSoft\GoRMG\Commercial\Models\ProformaInvoice;
use SkylarkSoft\GoRMG\Merchandising\Models\YarnPurchaseOrder;
use SkylarkSoft\GoRMG\SystemSettings\Models\Factory;
use SkylarkSoft\GoRMG\SystemSettings\Models\Supplier;
use SkylarkSoft\GoRMG\SystemSettings\Models\YarnStoreVariableSetting;
use SkylarkSoft\GoRMG\Inventory\Filters\Filter;
use SkylarkSoft\GoRMG\SystemSettings\Services\YarnStoreApprovalMaintainService;

class YarnReceive extends Model
{
    use SoftDeletes,CommonModelTrait;
    protected $table = 'yarn_receives';
    const INDEPENDENT = 'independent';
    protected $primaryKey = 'id';
    const PI_BASIS = 'pi';
    const WO_BASIS = 'wo';

    protected $fillable = [
        'receive_no',
        'factory_id',
        'receive_basis',
        'issue_challan_no',
        'receive_basis_id',
        'receive_basis_no',
        'receive_purpose',
        'lc_no',
        'lc_receive_date',
        'source',
        'store_id',
        'remarks',
        'challan_no',
        'receive_date',
        'currency_id',
        'exchange_rate',
        'loan_party_id',
        'ready_to_approve',
        'un_approve_request',
        'step',
        'is_approve',
        'approve_date',
        'created_by',
        'updated_by',
        'deleted_by'
    ];
    public static function booted()
    {
        static::created(function ($model) {
            $generate = str_pad($model->id, 5, "0", STR_PAD_LEFT);
            $model->receive_no = getPrefix() . 'YR-' . date('Y') . '-' . $generate;
            $model->save();
        });

        static::addGlobalScope('approvalMaintain', function (Builder $builder) {
            $isApproveMaintain = YarnStoreApprovalMaintainService::getApprovalMaintainStatus();

            if ($isApproveMaintain == 1) {
                $builder->where('is_approve', 1);
            }
        });
    }


    public function receivable(): MorphTo
    {
        return $this->morphTo('receivable', 'receive_basis', 'receive_basis_id');
    }

    public function store(): BelongsTo
    {
        return $this->belongsTo(Store::class, 'store_id')->withDefault();
    }

    public function details(): HasMany
    {
        return $this->hasMany(YarnReceiveDetail::class, 'yarn_receive_id');
    }

    public function factory(): BelongsTo
    {
        return $this->belongsTo(Factory::class, 'factory_id')->withDefault();
    }

    public function scopeYarnCount(Builder $query, $countId): Builder
    {
        if (!$countId) {
            return $query;
        }
        return $query->whereHas('details', function($q) use ($countId) {
            $q->where('yarn_count_id', $countId);
        });
    }

    public function scopeYarnComposition(Builder $query, $compositionId): Builder
    {
        if (!$compositionId) {
            return $query;
        }
        return $query->whereHas('details', function($q) use ($compositionId) {
            $q->where('yarn_composition_id', $compositionId);
        });
    }

    public function scopeYarnType(Builder $query, $typeId): Builder
    {
        if (!$typeId) {
            return $query;
        }
        return $query->whereHas('details', function($q) use ($typeId) {
            $q->where('yarn_type_id', $typeId);
        });
    }

    public function scopeApprovalFilter($query, $request, $previousStep, $step)
    {
        return $query->withoutGlobalScope('approvalMaintain')
            ->when($request->get('factory_id'), Filter::applyFilter('factory_id', $request->get('factory_id')))
            ->when($request->get('store_id'), Filter::applyFilter('store_id', $request->get('store_id')))
            ->when($request->get('challan_no'), Filter::applyFilter('challan_no', $request->get('challan_no')))
            ->when($request->get('receive_no'), Filter::applyFilter('receive_no', $request->get('receive_no')))
            ->when($request->get('receive_date'), Filter::applyFilter('receive_date', $request->get('receive_date')))
            ->when($request->get('approvalType') == 1, function ($query) use ($request, $step) {
                $query->where('ready_to_approve', $request->get('approvalType'))
                    ->where('is_approve', '=', null)
                    ->where('step', $step - 1);
            })->when($request->get('approvalType') == 2, function ($query) use ($step) {
                $query->where('step', $step);
            });
    }

    public function loanParty(): BelongsTo
    {
        return $this->belongsTo(Supplier::class, 'loan_party_id', 'id')->withDefault();
    }

    public function wo(): BelongsTo
    {
        return $this->belongsTo(YarnPurchaseOrder::class, 'receive_basis_id', 'id');
    }

    public function pi(): BelongsTo
    {
        return $this->belongsTo(ProformaInvoice::class, 'receive_basis_id', 'id')->withDefault();
    }

    public function yarnReceiveReturn(): HasMany
    {
        return $this->hasMany(YarnReceiveReturn::class, 'receive_id');
    }

    public function getLcReceiveDateFormatAttribute()
    {
        if (isset($this->attributes['lc_receive_date'])) {
            return date('d-m-Y', strtotime($this->attributes['lc_receive_date']));
        }

        return '';
    }
}
