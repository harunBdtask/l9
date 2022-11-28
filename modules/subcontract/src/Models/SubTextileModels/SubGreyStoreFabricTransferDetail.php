<?php

namespace SkylarkSoft\GoRMG\Subcontract\Models\SubTextileModels;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;
use SkylarkSoft\GoRMG\Knitting\Traits\CommonBooted;

class SubGreyStoreFabricTransferDetail extends Model
{
    use SoftDeletes;
    use CommonBooted;

    protected $primaryKey = "id";
    protected $table = "sub_grey_store_fabric_transfer_details";
    protected $fillable = [
        'transfer_detail_uid',
        'fabric_transfer_id',
        'criteria',
        'transfer_date',
        'transfer_type',
        'from_store_id',
        'from_order_id',
        'from_supplier_id',
        'from_order_detail_id',
        'from_remarks',
        'to_store_id',
        'to_order_id',
        'to_supplier_id',
        'to_order_detail_id',
        'to_remarks',
        'transfer_qty',
        'rate',
        'amount',
        'created_by',
        'updated_by',
        'deleted_by',
    ];

    public function transfer(): BelongsTo
    {
        return $this->belongsTo(SubGreyStoreFabricTransfer::class, 'fabric_transfer_id')
            ->withDefault();
    }

    public function detailMSI(): HasOne
    {
        return $this->hasOne(SubGreyStoreFabricTransferDetailMSI::class, 'transfer_detail_id');
    }

    public function fromOrder(): BelongsTo
    {
        return $this->belongsTo(SubTextileOrder::class, 'from_order_id')->withDefault();
    }

    public function toOrder(): BelongsTo
    {
        return $this->belongsTo(SubTextileOrder::class, 'to_order_id')->withDefault();
    }

    public function fromOrderDetail(): BelongsTo
    {
        return $this->belongsTo(SubTextileOrderDetail::class, 'from_order_detail_id')->withDefault();
    }

    public function toOrderDetail(): BelongsTo
    {
        return $this->belongsTo(SubTextileOrderDetail::class, 'to_order_detail_id')->withDefault();
    }
}
