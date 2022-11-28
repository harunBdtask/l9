<?php

namespace SkylarkSoft\GoRMG\HR\Models;

use App\ModelCommonTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class HrDisciplineDetail extends Model
{
    use SoftDeletes, ModelCommonTrait;

    protected $table = 'hr_discipline_details';

    protected $fillable = [
        'discipline_id',
        'employee_id',
        'amount',
        'deduction_month',
        'suspended_from',
        'suspended_to',
        'termination_date',
        'factory_id',
        'created_by',
        'updated_by',
        'deleted_by'
    ];
}
