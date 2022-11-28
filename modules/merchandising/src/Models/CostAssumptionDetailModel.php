<?php

namespace SkylarkSoft\GoRMG\Merchandising\Models;

use Illuminate\Database\Eloquent\Model;

class CostAssumptionDetailModel extends Model
{
    protected $table = 'cost_assumption_details';
    protected $primaryKey = 'id';
    protected $fillable = [
        'cost_assumption_id',
        'item_id',
        'finish_fab_cost',
        'trims_accessories',
        'cost_of_manufacturing',
        'others_cost',
        'profit_percentage',
        'item_unit_cost',
        'set_information',
        'factory_id',
        'created_by',
        'updated_by',
        'deleted_by',
        'deleted_at',
        'created_at',
        'updated_at',
    ];
}
