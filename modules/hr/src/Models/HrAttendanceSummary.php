<?php

namespace SkylarkSoft\GoRMG\HR\Models;

use App\ModelCommonTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class HrAttendanceSummary extends Model
{

    use SoftDeletes, ModelCommonTrait;

    protected $table = 'hr_attendance_summaries';

    protected $fillable = [
        'userid',
        'date',
        'att_in',
        'status',
        'lunch_in',
        'lunch_out',
        'att_out',
        'total_work_hour',
        'approved_ot_hour',
        'total_in_day_ot_hour',
        'regular_ot_hour',
        'extra_ot_hour_same_day',
        'unapproved_ot_hour',
        'extra_ot_hour_next_day',
        'present_status',
        'working_day_type',
        'ot_eligible_status',
        'shift_status',
        'total_work_minute',
        'approved_ot_minute',
        'total_in_day_ot_minute',
        'regular_ot_minute',
        'extra_ot_minute_same_day',
        'unapproved_ot_minute',
        'extra_ot_minute_next_day',
        'night_start',
        'night_end',
        'total_night_hour',
        'approved_night_start',
        'approved_night_end',
        'total_approved_ot_hour',
        'total_night_ot_hour',
        'unapproved_night_ot_hour',
        'total_night_minute',
        'total_approved_ot_minute',
        'total_night_ot_minute',
        'unapproved_night_ot_minute',
        'night_ot_eligible_status',
        'factory_id',
        'created_by',
        'updated_by',
        'deleted_by'
    ];

    const NIGHT_OT_ELIGIBLE = 1;
    const NIGHT_OT_INELIGIBLE = 0;
    const REGULAR_WORKING_DAY = 1;
    const HOLIDAY_WORKING_DAY = 2;
    const SHIFT_ENABLE = 1;
    const SHIFT_DISABLE = 0;

//    public function employeeBasicInfo(): BelongsTo
//    {
//        return $this->belongsTo(HrEmployee::class, 'userid', 'unique_id')->withDefault();
//    }

    public function employeeOfficialInfo(): BelongsTo
    {
        return $this->belongsTo(HrEmployeeOfficialInfo::class, 'userid', 'punch_card_id')->withDefault();
    }
}
