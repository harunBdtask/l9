<?php

namespace SkylarkSoft\GoRMG\HR\Models;

use App\ModelCommonTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;

class HrAttendance extends Model
{

    use SoftDeletes,ModelCommonTrait;

    protected $table = 'hr_machine_attendances';

    protected $fillable = [
        'idattendance_sheet',
        'userid',
        'date',
        'daytype',
        'leavetype',
        'sche1',
        'shiftNo',
        'att_in',
        'att_break',
        'att_resume',
        'att_out',
        'att_ot',
        'att_done',
        'workhour',
        'othour',
        'manual_absent_status',
        'factory_id',
        'created_by',
        'updated_by',
        'deleted_by',
    ];

    public function employeeOfficialInfo(): HasOne
    {
        return $this->hasOne(HrEmployeeOfficialInfo::class, 'unique_id', 'userid')->withDefault();
    }

    public function attendance_summary(): HasOne
    {
        return $this->hasOne(HrAttendanceSummary::class, 'userid', 'userid')->withDefault();
    }

}
