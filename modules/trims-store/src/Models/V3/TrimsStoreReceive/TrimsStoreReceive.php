<?php

namespace SkylarkSoft\GoRMG\TrimsStore\Models\V3\TrimsStoreReceive;

use App\Models\BelongsToFactory;
use App\Models\BelongsToStore;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Http\Request;
use SkylarkSoft\GoRMG\TrimsStore\Filters\Filter;
use SkylarkSoft\GoRMG\TrimsStore\Services\V3\UID\TrimsStoreReceiveService;
use SkylarkSoft\GoRMG\TrimsStore\Traits\CommonBooted;

class TrimsStoreReceive extends Model
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

    const RECEIVE_BASIS = [
        1 => 'Main BK Work Order Basis',
        2 => 'Short BK Work Order Basis',
        3 => 'PI Basis',
        4 => 'Independent Basis',
        5 => 'Sample Work Order Basis',
    ];

    const PAY_MODES = [
        1 => 'Based On Booking',
        2 => 'Credit',
        3 => 'Import',
    ];

    const READY_TO_APPROVE = [
        1 => 'No',
        2 => 'Yes',
    ];

    protected $table = 'v3_trims_store_receives';
    protected $primaryKey = 'id';
    protected $fillable = [
        'unique_id',
        'factory_id',
        'source_id',
        'store_id',
        'receive_basis_id',
        'challan_no',
        'receive_date',
        'pi_numbers',
        'pi_receive_date',
        'lc_no',
        'lc_receive_date',
        'pay_mode_id',
        'ready_to_approve',
        'remarks',
        'created_by',
        'updated_by',
        'deleted_by',
    ];

    protected $appends = [
        'source',
        'receive_basis',
        'pay_mode',
    ];

    public function getSourceAttribute(): ?string
    {
        return isset($this->attributes['source_id'])
            ? self::SOURCES[$this->attributes['source_id']]
            : null;
    }

    public function getReceiveBasisAttribute(): ?string
    {
        return isset($this->attributes['receive_basis_id'])
            ? self::RECEIVE_BASIS[$this->attributes['receive_basis_id']]
            : null;
    }

    public function getPayModeAttribute(): ?string
    {
        return isset($this->attributes['pay_mode_id'])
            ? self::PAY_MODES[$this->attributes['pay_mode_id']]
            : null;
    }

    public static function booted()
    {
        static::saving(function ($model) {
            if (! $model->id && in_array('created_by', $model->getFillable())) {
                $model->unique_id = TrimsStoreReceiveService::generateUniqueId();
            }
        });
    }

    public function scopeSearch(Builder $query, Request $request)
    {
        return $query->when($request, function (Builder $query) use ($request) {
            $uniqueId = $request->query('unique_id');
            $factoryId = $request->query('factory_id');
            $sourceId = $request->query('source_id');
            $storeId = $request->query('store_id');
            $receiveBasisId = $request->query('receive_basis_id');
            $challanNo = $request->query('challan_no');
            $receiveDate = $request->query('receive_date');
            $payModeId = $request->query('pay_mode_id');

            return $query->when($factoryId, Filter::applyFilter('factory_id', $factoryId))
                ->when($sourceId, Filter::applyFilter('source_id', $sourceId))
                ->when($storeId, Filter::applyFilter('store_id', $storeId))
                ->when($receiveBasisId, Filter::applyFilter('receive_basis_id', $receiveBasisId))
                ->when($receiveDate, Filter::applyFilter('receive_date', $receiveDate))
                ->when($payModeId, Filter::applyFilter('pay_mode_id', $payModeId))
                ->when($uniqueId, function (Builder $query) use ($uniqueId) {
                    return $query->where('unique_id', 'LIKE', "%{$uniqueId}%");
                })->when($challanNo, function (Builder $query) use ($challanNo) {
                    return $query->where('challan_no', 'LIKE', "%{$challanNo}%");
                });
        });
    }

    public function details(): HasMany
    {
        return $this->hasMany(TrimsStoreReceiveDetail::class, 'trims_store_receive_id');
    }
}
