<?php


namespace SkylarkSoft\GoRMG\SystemSettings\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CommercialCostMethod extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $table = 'commercial_cost_methods';
    protected $guarded = [];
}
