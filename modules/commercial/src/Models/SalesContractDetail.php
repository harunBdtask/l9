<?php

namespace SkylarkSoft\GoRMG\Commercial\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use SkylarkSoft\GoRMG\Merchandising\Models\PurchaseOrder;
use SkylarkSoft\GoRMG\Merchandising\Models\Order;

class SalesContractDetail extends Model
{
    protected $table = 'sales_contract_details';

    protected $fillable = [
        'sales_contract_id',
        'po_id',
        'order_id',
        'attach_qty',
        'rate',
        'attach_value',
    ];

    /**
     * @return BelongsTo
     */
    public function po(): BelongsTo
    {
        return $this->belongsTo(PurchaseOrder::class, 'po_id')->withDefault();
    }

    public function order(): BelongsTo
    {
        return $this->belongsTo(PurchaseOrder::class, 'order_id')->withDefault();
    }

    public function orders(): BelongsTo
    {
        return $this->belongsTo(Order::class, 'order_id')->withDefault();
    }

    public function salesContract(): BelongsTo
    {
        return $this->belongsTo(SalesContract::class, 'sales_contract_id')->withDefault();
    }
}
