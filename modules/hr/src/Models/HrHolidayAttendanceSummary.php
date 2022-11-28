<?php

namespace SkylarkSoft\GoRMG\HR\Models;

use App\ModelCommonTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class HrHolidayAttendanceSummary extends Model
{
    use SoftDeletes, ModelCommonTrait;

    protected $table = 'hr_holiday_attendance_summaries';

    protected $fillable = [
        'userid',
        'date',
        'att_in',
        'att_out',
        'total_work_hour',
        'total_work_minute',
        'approved_start',
        'approved_end',
        'approved_hour',
        'approved_minute',
        'total_approved_work_hour',
        'total_approved_work_minute',
        'total_unapproved_work_hour',
        'total_unapproved_work_minute',
        'ot_eligible_status',
        'factory_id',
        'created_by',
        'updated_by',
        'deleted_by'
    ];

    const OT_ELIGIBLE = 1;
    const OT_INELIGIBLE = 0;

    public function employeeBasicInfo(): BelongsTo
    {
        return $this->belongsTo(HrEmployee::class, 'userid', 'unique_id')->withDefault();
    }

    public function employeeOfficialInfo(): BelongsTo
    {
        return $this->belongsTo(HrEmployeeOfficialInfo::class, 'userid', 'unique_id')->withDefault();
    }
}
