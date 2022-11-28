<?php

namespace SkylarkSoft\GoRMG\Merchandising\Models;

use App\FactoryIdTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CostAssumptionModel extends Model
{
    use FactoryIdTrait;
    use SoftDeletes;
    protected $table = 'cost_assumptions';
    protected $primaryKey = 'id';
    protected $fillable = [
        'sample_ref_no',
        'buyer_id',
        'agent_id',
        'currency_id',
        'cost_per_set',
        'factory_id',
        'created_by',
        'updated_by',
        'deleted_by',
        'deleted_at',
        'created_at',
        'updated_at',
    ];
}
