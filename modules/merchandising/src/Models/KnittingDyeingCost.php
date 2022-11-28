<?php

namespace SkylarkSoft\GoRMG\Merchandising\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class KnittingDyeingCost extends Model
{
    use SoftDeletes;

    protected $table = 'knitting_dyeing_costs';

    protected $fillable = [
        'type',
        'pre_budget_id',
        'fabric_composition_id',
        'quantity',
        'unit_price',
        'shipment_mode',
        'total', // quantity * unit_price
        'payment_mode',
        'supplier_name',
        'remarks',
    ];
}
