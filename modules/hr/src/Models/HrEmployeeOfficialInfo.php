<?php

namespace SkylarkSoft\GoRMG\HR\Models;

use App\ModelCommonTrait;
use Carbon\Carbon;
use Closure;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;

class HrEmployeeOfficialInfo extends Model
{
    use SoftDeletes, ModelCommonTrait;

    const REGULAR = 0;
    const TERMINATED = 1;

    protected $table = 'hr_employee_official_infos';
    protected $fillable = [
        'id',
        'employee_id',
        'department_id',
        'designation_id',
        'work_type_id',
        'section_id',
        'group_id',
        'grade_id',
        'code',
        'type',
        'unique_id',
        'punch_card_id',
        'date_of_joining',
        'job_permanent_date',
        'date_of_joining_bn',
        'bgmea_id',
        'bank_id',
        'account_no',
        'reporting_to',
        'shift_enabled',
        'shift_id',
        'created_at',
        'updated_at',
        'termination_status',
        'termination_date',
        'factory_id',
        'created_by',
        'updated_by',
        'deleted_by'
    ];

    protected static function boot()
    {
        parent::boot();
        static::created(Closure::fromCallable([__CLASS__, 'uniqueIdObserver']));
        static::updated(Closure::fromCallable([__CLASS__, 'uniqueIdObserver']));
    }


    public static function uniqueIdObserver($model)
    {
        $employee = HrEmployee::find($model->employee_id);
        $employee->unique_id = $model->unique_id;
        $employee->save();
    }

    public function setDateOfJoiningAttribute($value)
    {
        $this->attributes['date_of_joining'] = $value ? Carbon::make($value)->format('Y-m-d') : null;
    }

    public function getDateOfJoiningAttribute(): string
    {
        if (isset($this->attributes['date_of_joining'])) {
            return Carbon::make($this->attributes['date_of_joining'])->format('d-m-Y');
        }
        return '';
    }

    public function setJobPermanentDateAttribute($value)
    {
        $this->attributes['job_permanent_date'] = $value ? Carbon::make($value)->format('Y-m-d') : null;
    }

    public function getJobPermanentDateAttribute(): string
    {
        if (isset($this->attributes['job_permanent_date'])) {
            return Carbon::make($this->attributes['job_permanent_date'])->format('d-m-Y');
        }
        return '';
    }

    public function attendances()
    {
        return $this->hasMany(HrAttendanceRawData::class, 'userid', 'punch_card_id');
    }
    public function last_attendence_dates(){
        return $this->hasMany(HrAttendanceRawData::class, 'userid', 'punch_card_id');
    }
    public function employeeBasicInfo(): BelongsTo
    {
        return $this->belongsTo(HrEmployee::class, 'employee_id')->withDefault();
    }

    public function departmentDetails(): BelongsTo
    {
        return $this->belongsTo(HrDepartment::class, 'department_id')->withDefault();
    }

    public function designationDetails(): BelongsTo
    {
        return $this->belongsTo(HrDesignation::class, 'designation_id', 'id')->withDefault();
    }

    public function sectionDetails(): BelongsTo
    {
        return $this->belongsTo(HrSection::class, 'section_id', 'id')->withDefault();
    }

    public function group(): BelongsTo
    {
        return $this->belongsTo(HrGroup::class, 'group_id')->withDefault();
    }

    public function grade(): BelongsTo
    {
        return $this->belongsTo(HrGrade::class, 'grade_id')->withDefault();
    }

    public function salary(): HasOne
    {
        return $this->hasOne(HrEmployeeSalaryInfo::class, 'employee_id', 'employee_id')->withDefault();
    }

    public function attendanceRawData()
    {
        return $this->belongsTo(HrAttendanceRawData::class, 'punch_card_id', 'userid')->withDefault();
    }

    public function attendanceSummary()
    {
        return $this->belongsTo(HrAttendanceSummary::class, 'punch_card_id', 'userid')->withDefault();
    }



    /*
     * @description migrates unique id from employee official info to employee table
     */

    public static function migrateUniqueId()
    {
        $employeeOfficialInfos = self::all();
        foreach ($employeeOfficialInfos as $employeeOfficialInfo) {
            $employee = HrEmployee::find($employeeOfficialInfo->employee_id);
            if (!empty($employee) && $employee->unique_id != $employeeOfficialInfo->unique_id) {
                $employee->unique_id = $employeeOfficialInfo->unique_id;
                $employee->save();
            }
        }
    }

    public function bank(): BelongsTo
    {
        return $this->belongsTo(HrBank::class, 'bank_id')->withDefault();
    }

    public function shift(): BelongsTo
    {
        return $this->belongsTo(HrShift::class, 'shift_id')->withDefault();
    }

    public function workType(): BelongsTo {
        return $this->belongsTo(HrEmployeeWorkType::class, 'work_type_id', 'id')->withDefault();
    }
}
