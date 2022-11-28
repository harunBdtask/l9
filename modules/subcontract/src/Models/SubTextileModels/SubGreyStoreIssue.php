<?php

namespace SkylarkSoft\GoRMG\Subcontract\Models\SubTextileModels;

use App\Models\BelongsToFactory;
use App\Models\CommonModelTrait;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use SkylarkSoft\GoRMG\Subcontract\Filters\Filter;
use SkylarkSoft\GoRMG\Subcontract\Models\Libraries\SubContractGreyStore;
use SkylarkSoft\GoRMG\Subcontract\Models\Libraries\SubDyeingUnit;
use SkylarkSoft\GoRMG\Subcontract\Services\SubTextileServices\UId\SubGreyStoreIssueService;
use SkylarkSoft\GoRMG\SystemSettings\Models\Buyer;

class SubGreyStoreIssue extends Model
{
    use SoftDeletes;
    use CommonModelTrait;
    use BelongsToFactory;

    public const ISSUE_PURPOSE = [
        1 => 'Dyeing and Finishing',
        2 => 'Dyeing',
        3 => 'AOP',
        4 => 'Finishing',
    ];

    protected $table = 'sub_grey_store_issues';
    protected $primaryKey = 'id';
    protected $fillable = [
        'factory_id',
        'supplier_id',
        'sub_textile_order_id',
        'sub_grey_store_id',
        'sub_dyeing_unit_id',
        'challan_no',
        'challan_date',
        'issue_purpose',
        'remarks',
        'created_by',
        'updated_by',
        'deleted_by',
    ];

    protected $appends = [
        'issue_purpose_value',
    ];

    public function getIssuePurposeValueAttribute(): ?string
    {
        return self::ISSUE_PURPOSE[$this->attributes['issue_purpose']] ?? null;
    }

    public static function boot()
    {
        parent::boot();

        static::saving(function ($model) {
            if (! $model->id && in_array('created_by', $model->getFillable())) {
                $model->challan_no = SubGreyStoreIssueService::generateChallanNo($model->sub_textile_order_id);
            }
        });
    }

    public function scopeSearch(Builder $query, $request)
    {
        $query->when($request, function (Builder $builder) use ($request) {
            $builder->when($request->get('factory_id'), Filter::applyFilter('factory_id', $request->get('factory_id')))
                ->when($request->get('supplier_id'), Filter::applyFilter('supplier_id', $request->get('supplier_id')))
                ->when($request->get('issue_purpose'), Filter::applyFilter('issue_purpose', $request->get('issue_purpose')))
                ->when($request->get('challan_no'), Filter::applyFilter('challan_no', $request->get('challan_no')))
                ->when($request->get('challan_date'), Filter::applyFilter('challan_date', $request->get('challan_date')))
                ->when($request->get('sub_textile_order_id'), function (Builder $builder) use ($request) {
                    $builder->whereHas('textileOrder', function (Builder $builder) use ($request) {
                        $builder->where('order_no', 'LIKE', "%{$request->get('sub_textile_order_id')}%");
                    });
                });
        });
    }

    public function textileOrder(): BelongsTo
    {
        return $this->belongsTo(SubTextileOrder::class, 'sub_textile_order_id', 'id')
            ->withDefault();
    }

    public function supplier(): BelongsTo
    {
        return $this->belongsTo(Buyer::class, 'supplier_id', 'id')->withDefault();
    }

    public function issueDetails(): HasMany
    {
        return $this->hasMany(SubGreyStoreIssueDetail::class, 'sub_grey_store_issue_id', 'id');
    }

    public function subGreyStore(): BelongsTo
    {
        return $this->belongsTo(SubContractGreyStore::class, 'sub_grey_store_id', 'id');
    }

    public function subDyeingUnit(): BelongsTo
    {
        return $this->belongsTo(SubDyeingUnit::class, 'sub_dyeing_unit_id', 'id')
            ->withDefault();
    }
}
