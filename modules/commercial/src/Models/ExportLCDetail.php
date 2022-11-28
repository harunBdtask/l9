<?php

namespace SkylarkSoft\GoRMG\Commercial\Models;

use Illuminate\Database\Eloquent\Model;
use SkylarkSoft\GoRMG\Merchandising\Models\Order;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use SkylarkSoft\GoRMG\Merchandising\Models\PurchaseOrder;

class ExportLCDetail extends Model
{
    protected $table = 'export_lc_details';

    protected $fillable = [
        'export_contract_id',
        'po_id',
        'order_id',
        'attach_qty',
        'rate',
        'attach_value',
    ];

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
        return $this->belongsTo(ExportLC::class, 'export_lc_id')->withDefault();
    }
}
