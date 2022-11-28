<?php

namespace SkylarkSoft\GoRMG\TrimsStore\Models\V3\TrimsStoreReceiveReturn;

use App\Models\BelongsToFactory;
use App\Models\BelongsToStore;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Http\Request;
use SkylarkSoft\GoRMG\TrimsStore\Filters\Filter;
use SkylarkSoft\GoRMG\TrimsStore\Services\V3\UID\TrimsStoreReceiveReturn\TrimsStoreReceiveReturnService;
use SkylarkSoft\GoRMG\TrimsStore\Traits\CommonBooted;

class TrimsStoreReceiveReturn extends Model
{
    use SoftDeletes;
    use CommonBooted;
    use BelongsToFactory;
    use BelongsToStore;

    const SOURCES = [
        1 => 'In-House',
        2 => 'Out-Bound',
    ];

    const RETURN_TYPES = [
        1 => 'Manually',
        2 => 'Barcode',
    ];

    const RETURN_BASIS = [
        1 => 'Work Order Basis',
        2 => 'PI Basis',
        3 => 'Independent Basis',
    ];

    const READY_TO_APPROVE = [
        1 => 'No',
        2 => 'Yes',
    ];

    protected $table = 'v3_trims_store_receive_returns';
    protected $primaryKey = 'id';
    protected $fillable = [
        'unique_id',
        'factory_id',
        'returned_source_id',
        'return_date',
        'return_type_id',
        'return_basis_id',
        'store_id',
        'gate_pass_no',
        'ready_to_approve_id',
        'remarks',
        'created_by',
        'updated_by',
        'deleted_by',
    ];

    protected $appends = [
        'source',
        'return_basis',
        'return_type',
        'ready_to_approve_value',
    ];

    public static function booted()
    {
        static::saving(function ($model) {
            if (! $model->id && in_array('created_by', $model->getFillable())) {
                $model->unique_id = TrimsStoreReceiveReturnService::generateUniqueId();
            }
        });
    }

    public function getSourceAttribute(): ?string
    {
        return self::SOURCES[$this->attributes['returned_source_id']] ?? null;
    }

    public function getReturnBasisAttribute(): ?string
    {
        return self::RETURN_BASIS[$this->attributes['return_basis_id']] ?? null;
    }

    public function getReturnTypeAttribute(): ?string
    {
        return self::RETURN_TYPES[$this->attributes['return_type_id']] ?? null;
    }

    public function getReadyToApproveValueAttribute(): ?string
    {
        return self::READY_TO_APPROVE[$this->attributes['ready_to_approve_id']] ?? null;
    }

    public function details(): HasMany
    {
        return $this->hasMany(TrimsStoreReceiveReturnDetail::class, 'trims_store_receive_return_id');
    }

    public function scopeSearch(Builder $query, Request $request)
    {
        return $query->when($request, function (Builder $query) use ($request) {
            $uniqueId = $request->query('unique_id');
            $factoryId = $request->query('factory_id');
            $sourceId = $request->query('source_id');
            $storeId = $request->query('store_id');
            $returnBasisId = $request->query('return_basis_id');
            $returnTypeId = $request->query('return_type_id');
            $returnDate = $request->query('return_date');
            $gatePassNo = $request->query('gate_pass_no');

            return $query->when($factoryId, Filter::applyFilter('factory_id', $factoryId))
                ->when($sourceId, Filter::applyFilter('returned_source_id', $sourceId))
                ->when($storeId, Filter::applyFilter('store_id', $storeId))
                ->when($returnBasisId, Filter::applyFilter('return_basis_id', $returnBasisId))
                ->when($returnTypeId, Filter::applyFilter('return_type_id', $returnTypeId))
                ->when($returnDate, Filter::applyFilter('return_date', $returnDate))
                ->when($uniqueId, function (Builder $query) use ($uniqueId) {
                    return $query->where('unique_id', 'LIKE', "%{$uniqueId}%");
                })->when($gatePassNo, function (Builder $query) use ($gatePassNo) {
                    return $query->where('gate_pass_no', 'LIKE', "%{$gatePassNo}%");
                });
        });
    }
}
