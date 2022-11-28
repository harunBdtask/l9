<?php

namespace SkylarkSoft\GoRMG\Knitting\Models;

use App\Casts\Json;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PlanningInfoDetail extends Model
{
    use SoftDeletes;
    protected $table = 'planning_info_details';
    public $timestamps = false;
    protected $fillable = [
        'total_qty',
        'percentage',
        'supplier_id',
        'yarn_color',
        'yarn_type_id',
        'yarn_count_id',
        'planning_info_id',
        'yarn_composition_id',
        'composition_type_id',
        'created_by',
        'updated_by',
        'deleted_by',
        'deleted_at',
        'created_at',
        'updated_at',
    ];
    protected $casts = [

    ];
}
