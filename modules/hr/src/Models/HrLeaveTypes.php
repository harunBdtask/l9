<?php

namespace SkylarkSoft\GoRMG\HR\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\ModelCommonTrait;

class HrLeaveTypes extends Model
{
    use ModelCommonTrait;

    protected $table = "hr_leave_types";

    protected $fillable = [
        'name',
        'name_bn',
        'factory_id',
        'created_by',
        'updated_by',
        'deleted_by'
    ];
    public function employeeTypes()
    {
        return $this->hasMany(HrLeaveSetting::class, 'leave_types_id', 'id');
    }
}
