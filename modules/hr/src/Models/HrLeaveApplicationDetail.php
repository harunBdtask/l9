<?php

namespace SkylarkSoft\GoRMG\HR\Models;

use App\ModelCommonTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;

class HrLeaveApplicationDetail extends Model
{
    use SoftDeletes,ModelCommonTrait;

    protected $table = 'hr_leave_application_details';

    protected $fillable = [
        'employee_id',
        'leave_id',
        'type_id',
        'leave_date',
        'factory_id',
        'created_by',
        'updated_by',
        'deleted_by'
    ];

    public function employee(): BelongsTo
    {
        return $this->belongsTo(HrEmployee::class, 'employee_id')->withDefault();
    }

    public function employeeOfficialInfo(): HasOne
    {
        return $this->hasOne(HrEmployeeOfficialInfo::class, 'employee_id', 'employee_id')->withDefault();
    }

    public function leave(): BelongsTo
    {
        return $this->belongsTo(HrLeaveApplication::class, 'leave_id')->withDefault();
    }

    public function type(): BelongsTo
    {
        return $this->belongsTo(HrLeaveSetting::class, 'type_id')->withDefault();
    }
}
