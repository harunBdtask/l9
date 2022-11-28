<?php

namespace SkylarkSoft\GoRMG\HR\Models;

use App\ModelCommonTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class HrEmployeeEducationInfo extends Model
{
    use SoftDeletes, ModelCommonTrait;

    protected $table = 'hr_employee_education_infos';
    protected $fillable = [
        'id',
        'employee_id',
        'degree',
        'institution',
        'board',
        'result',
        'year',
        'factory_id',
        'created_by',
        'updated_by',
        'deleted_by'
    ];
}
