<?php

namespace SkylarkSoft\GoRMG\SecurityControl\Models;

use App\FactoryIdTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class EmployeeTracking extends Model
{
    use SoftDeletes;
    use FactoryIdTrait;
    protected $table = 'employee_tracking';
    protected $dates = ['deleted_at'];
}
