<?php

namespace SkylarkSoft\GoRMG\Merchandising\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use SkylarkSoft\GoRMG\SystemSettings\Models\Item;

class TrimsAndAccessoriesCost extends Model
{
    use SoftDeletes;

    protected $table = 'pre_budget_trims_cost';

    protected $fillable = [
        'pre_budget_id',
        'item_id',
        'quantity',
        'unit_price',
        'total',  // quantity * unit_price
        'origin',
        'shipment_mode', // dropdown
        'payment_mode',
        'supplier_name',
        'remarks',
    ];

    public function item()
    {
        return $this->belongsTo(Item::class, 'item_id')->withDefault();
    }
}
