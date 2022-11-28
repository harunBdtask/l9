<?php

namespace SkylarkSoft\GoRMG\TrimsStore\Models\V3\TrimsStoreIssue;

use App\Models\BelongsToFactory;
use App\Models\BelongsToStore;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Http\Request;
use SkylarkSoft\GoRMG\TrimsStore\Filters\Filter;
use SkylarkSoft\GoRMG\TrimsStore\Models\V3\TrimsStoreReceive\TrimsStoreReceive;
use SkylarkSoft\GoRMG\TrimsStore\Services\V3\UID\TrimsStoreIssue\TrimsStoreIssueService;
use SkylarkSoft\GoRMG\TrimsStore\Traits\CommonBooted;

class TrimsStoreIssue extends Model
{
    use SoftDeletes;
    use CommonBooted;
    use BelongsToFactory;
    use BelongsToStore;

    const SOURCES = [
        1 => 'Based on Booking',
        2 => 'In-House',
        3 => 'Out-Bound',
        4 => 'Import',
    ];

    const ISSUE_BASIS = [
        1 => 'RCV Challan Basis',
        2 => 'Independent Basis',
        3 => 'Input Challan Basis',
        4 => 'PI Basis',
    ];

    const ISSUE_TYPE = [
        1 => 'Manually',
        2 => 'Barcode',
    ];

    const PAY_MODES = [
        1 => 'Based On Booking',
        2 => 'Manually',
        3 => 'Barcode',
        4 => 'Credit',
        5 => 'Import',
    ];

    const READY_TO_APPROVE = [
        1 => 'No',
        2 => 'Yes',
    ];

    protected $table = 'v3_trims_store_issues';
    protected $primaryKey = 'id';
    protected $fillable = [
        'unique_id',
        'factory_id',
        'source_id',
        'store_id',
        'issue_basis_id',
        'issue_type_id',
        'challan_no',
        'trims_store_receive_id',
        'issue_date',
        'pi_numbers',
        'pi_receive_date',
        'lc_no',
        'lc_receive_date',
        'issue_to',
        'pay_mode_id',
        'ready_to_approve',
        'remarks',
        'created_by',
        'updated_by',
        'deleted_by',
    ];

    protected $appends = [
        'source',
        'issue_basis',
        'issue_type',
        'pay_mode',
        'ready_to_approve_value',
    ];

    public function getSourceAttribute(): ?string
    {
        return self::SOURCES[$this->attributes['source_id']] ?? null;
    }

    public function getIssueBasisAttribute(): ?string
    {
        return self::ISSUE_BASIS[$this->attributes['issue_basis_id']] ?? null;
    }

    public function getIssueTypeAttribute(): ?string
    {
        return self::ISSUE_TYPE[$this->attributes['issue_type_id']] ?? null;
    }

    public function getPayModeAttribute(): ?string
    {
        return self::PAY_MODES[$this->attributes['pay_mode_id']] ?? null;
    }

    public function getReadyToApproveValueAttribute(): ?string
    {
        return self::READY_TO_APPROVE[$this->attributes['ready_to_approve']] ?? null;
    }

    public static function booted()
    {
        static::saving(function ($model) {
            if (! $model->id && in_array('created_by', $model->getFillable())) {
                $model->unique_id = TrimsStoreIssueService::generateUniqueId();
            }
        });
    }

    public function details(): HasMany
    {
        return $this->hasMany(TrimsStoreIssueDetail::class, 'trims_store_issue_id');
    }

    public function trimsStoreReceive(): BelongsTo
    {
        return $this->belongsTo(TrimsStoreReceive::class, 'trims_store_receive_id')
            ->withDefault();
    }

    public function scopeSearch(Builder $query, Request $request)
    {
        return $query->when($request, function (Builder $query) use ($request) {
            $uniqueId = $request->query('unique_id');
            $factoryId = $request->query('factory_id');
            $sourceId = $request->query('source_id');
            $storeId = $request->query('store_id');
            $issueBasisId = $request->query('issue_basis_id');
            $challanNo = $request->query('challan_no');
            $issueDate = $request->query('issue_date');
            $payModeId = $request->query('pay_mode_id');

            return $query->when($factoryId, Filter::applyFilter('factory_id', $factoryId))
                ->when($sourceId, Filter::applyFilter('source_id', $sourceId))
                ->when($storeId, Filter::applyFilter('store_id', $storeId))
                ->when($issueBasisId, Filter::applyFilter('issue_basis_id', $issueBasisId))
                ->when($issueDate, Filter::applyFilter('issue_date', $issueDate))
                ->when($payModeId, Filter::applyFilter('pay_mode_id', $payModeId))
                ->when($uniqueId, function (Builder $query) use ($uniqueId) {
                    return $query->where('unique_id', 'LIKE', "%{$uniqueId}%");
                })->when($challanNo, function (Builder $query) use ($challanNo) {
                    return $query->where('challan_no', 'LIKE', "%{$challanNo}%");
                });
        });
    }
}
