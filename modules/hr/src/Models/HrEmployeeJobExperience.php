<?php

namespace SkylarkSoft\GoRMG\HR\Models;

use App\ModelCommonTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class HrEmployeeJobExperience extends Model
{
    use SoftDeletes, ModelCommonTrait;

    protected $table = 'hr_employee_job_experiences';
    protected $fillable = [
        'id',
        'employee_id',
        'company_name',
        'ex_job_designation',
        'from_date',
        'to_date',
        'ex_job_salary',
        'leave_reason',
        'factory_id',
        'created_by',
        'updated_by',
        'deleted_by'
    ];
}
