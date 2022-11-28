<?php

namespace SkylarkSoft\GoRMG\HR\Models;

use App\ModelCommonTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class HrEmployeeSalaryInfo extends Model
{

    use SoftDeletes, ModelCommonTrait;

    protected $table = 'hr_employee_salary_infos';
    protected $fillable = [
        'id',
        'employee_id',
        'gross',
        'basic',
        'house_rent',
        'transport',
        'medical',
        'food',
        'out_of_city',
        'mobile_allowence',
        'attendance_bonus',
        'extra',
        'reason',
        'created_at',
        'updated_at',
        'factory_id',
        'created_by',
        'updated_by',
        'deleted_by'
    ];

    public function employee(): BelongsTo
    {
        return $this->belongsTo(HrEmployee::class)->withDefault();
    }
}
