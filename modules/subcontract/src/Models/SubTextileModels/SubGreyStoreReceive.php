<?php

namespace SkylarkSoft\GoRMG\Subcontract\Models\SubTextileModels;

use App\Models\BelongsToFactory;
use App\Models\CommonModelTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use SkylarkSoft\GoRMG\Subcontract\Models\Libraries\SubContractGreyStore;
use SkylarkSoft\GoRMG\SystemSettings\Models\Buyer;

/**
 * @property BelongsTo textileOrder
 * @property BelongsTo supplier
 * @property Hasmany receiveDetails
 * @property Hasmany barcodes
 * @property BelongsTo greyStore
 */
class SubGreyStoreReceive extends Model
{
    use SoftDeletes;
    use CommonModelTrait;
    use BelongsToFactory;

    const INDEPENDENT_BASIS_RECEIVE = 1;
    const ORDER_BASIS_RECEIVE = 2;

    protected $table = "sub_grey_store_receives";
    protected $fillable = [
        'factory_id',
        'supplier_id',
        'receive_basis',
        'sub_textile_order_id',
        'sub_grey_store_id',
        'challan_no',
        'challan_date',
        'required_operations',
        'remarks',
    ];

    protected $appends = [
        'receive_basis_value',
    ];

    public function getReceiveBasisValueAttribute(): ?string
    {
        return ($this->attributes['receive_basis'] == 1 ? 'Independent Basis' : 'Order Basis') ?? null;
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

    public function receiveDetails(): HasMany
    {
        return $this->hasMany(SubGreyStoreReceiveDetails::class, 'sub_grey_store_receive_id', 'id');
    }

    public function receiveDetailsByChallanNo(): HasMany
    {
        return $this->hasMany(SubGreyStoreReceiveDetails::class, 'challan_no', 'challan_no');
    }

    public function barcodes(): HasMany
    {
        return $this->hasMany(SubGreyStoreBarcodeDetail::class, 'sub_grey_store_receive_id');
    }

    public function greyStore(): BelongsTo
    {
        return $this->belongsTo(SubContractGreyStore::class, 'sub_grey_store_id')->withDefault();
    }

    public function challanOrders(): HasMany
    {
        return $this->hasMany(SubGreyStoreReceive::class, 'challan_no', 'challan_no');
    }
}
