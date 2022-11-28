<?php

namespace SkylarkSoft\GoRMG\HR\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HrEmployeeWorkType extends Model
{
    use HasFactory;

    protected $table = "hr_employee_work_types";
    protected $fillable = ['id', 'name_bn'];
}
