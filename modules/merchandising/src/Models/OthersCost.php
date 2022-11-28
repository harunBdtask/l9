<?php

namespace SkylarkSoft\GoRMG\Merchandising\Models;

use Illuminate\Database\Eloquent\Model;

class OthersCost extends Model
{
    protected $table = 'pre_budget_others_costs';

    const OPTIONS = [
        'print_emb' => 'PRINT/EMB/ETC',
        'quilting' => 'QUILTING',
        'inspection' => 'INSPECTION PURPOSE',
        'test' => 'TEST',
        'local' => 'LOCAL PURCHASE',
        'commission' => 'COMMISSION',
        'hidden_charge' => 'HIDDEN CHARGE',
        'cpt' => 'CPT',
    ];

    protected $fillable = [
        'name',
        'quantity',
        'unit_price',
        'shipment_mode',
        'total',
        'payment_mode',
        'supplier_name',
        'remarks',
    ];
}
