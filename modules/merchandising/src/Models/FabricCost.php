<?php

namespace SkylarkSoft\GoRMG\Merchandising\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class FabricCost extends Model
{
    use SoftDeletes;

    protected $table = 'pre_budget_fabric_costs';

    protected $fillable = [
        'pre_budget_id',
        'fabric_composition_id',
        'quantity',
        'unit_price',
        // quantity * unit_price
        'total',
        'shipment_mode',
        'payment_mode',
        'supplier_name',
        'remarks',
    ];

    public function composition()
    {
        return $this->belongsTo(Fabric_composition::class, 'fabric_composition_id')->withDefault();
    }
}
