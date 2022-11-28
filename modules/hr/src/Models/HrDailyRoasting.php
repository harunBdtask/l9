<?php

namespace SkylarkSoft\GoRMG\HR\Models;

use App\ModelCommonTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;

class HrDailyRoasting extends Model
{
    use SoftDeletes, ModelCommonTrait;

    const OFF_DAY = 1;
    const REGULAR_DAY = 0;

    protected $fillable = [
        'off_day_status',
        'employee_id',
        'shift_id',
        'date',
        'factory_id',
        'created_by',
        'updated_by',
        'deleted_by'
    ];

    protected $table = 'hr_daily_roastings';

    public function employee(): BelongsTo
    {
        return $this->belongsTo(HrEmployee::class, 'employee_id')->withDefault();
    }

    public function employeeOfficialInfo(): HasOne
    {
        return $this->hasOne(HrEmployeeOfficialInfo::class, 'employee_id', 'employee_id')->withDefault();
    }

    public function shift(): BelongsTo
    {
        return $this->belongsTo(HrShift::class, 'shift_id')->withDefault();
    }
}
