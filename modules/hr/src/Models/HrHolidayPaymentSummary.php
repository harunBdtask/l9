<?php

namespace SkylarkSoft\GoRMG\HR\Models;

use App\ModelCommonTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class HrHolidayPaymentSummary extends Model
{

    use SoftDeletes,ModelCommonTrait;

    protected $table = 'hr_holiday_payment_summaries';

    protected $fillable = [
        'userid',
        'pay_month',
        'total_working_holiday',
        'total_working_hour_time',
        'total_working_hour',
        'total_working_minute',
        'payment_rate',
        'total_payable_amount',
        'paid_status',
        'factory_id',
        'created_by',
        'updated_by',
        'deleted_by'
    ];

    public function employeeBasicInfo(): BelongsTo
    {
        return $this->belongsTo(HrEmployee::class, 'userid', 'unique_id')->withDefault();
    }

    public function employeeOfficialInfo(): BelongsTo
    {
        return $this->belongsTo(HrEmployeeOfficialInfo::class, 'userid', 'unique_id')->withDefault();
    }
}
