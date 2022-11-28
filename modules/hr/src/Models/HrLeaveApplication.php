<?php

namespace SkylarkSoft\GoRMG\HR\Models;

use App\ModelCommonTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class HrLeaveApplication extends Model
{
    use SoftDeletes,ModelCommonTrait;

    protected $table = 'hr_leave_applications';

    protected $fillable = [
        "department_id",
        "designation_id",
        "section_id",
        "employee_id",
        "unique_id",
        "applicant_name",
        "reason",
        "duration",
        "application_date",
        "leave_start",
        "leave_end",
        "rejoin_date",
        "contact_details",
        "application_for",
        "is_approved",
        "code",
        "type",
        "total_days",
        'factory_id',
        'created_by',
        'updated_by',
        'deleted_by'
    ];

    public function employee(): BelongsTo
    {
        return $this->belongsTo(HrEmployee::class, 'employee_id')->withDefault();
    }

    public function designation(): BelongsTo
    {
        return $this->belongsTo(HrDesignation::class, 'designation_id')->withDefault();
    }

    public function department(): BelongsTo
    {
        return $this->belongsTo(HrDepartment::class, 'department_id')->withDefault();
    }

    public function section(): BelongsTo
    {
        return $this->belongsTo(HrSection::class, 'section_id')->withDefault();
    }

    public function details(): HasMany
    {
        return $this->hasMany(HrLeaveApplicationDetail::class, 'leave_id');
    }
}
