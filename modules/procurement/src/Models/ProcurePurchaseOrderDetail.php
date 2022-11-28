<?php

namespace SkylarkSoft\GoRMG\Procurement\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use SkylarkSoft\GoRMG\SystemSettings\Models\ItemGroup;

class ProcurePurchaseOrderDetail extends Model
{
    use HasFactory;

    protected $table = 'procure_purchase_order_details';
    protected $fillable = [
        'purchase_order_id',
        'item_id',
        'quotation_id',
        'qty',
        'unit_price',
    ];
    public $timestamps = false;

    public function item(): ?BelongsTo
    {
        return $this->belongsTo(ItemGroup::class, 'item_id')->withDefault();
    }

    public function quotation(): ?BelongsTo
    {
        return $this->belongsTo(ProcureQuotation::class, 'quotation_id')->withDefault();
    }
}
