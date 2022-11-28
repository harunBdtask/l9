<?php

namespace SkylarkSoft\GoRMG\HR\Models;

use App\ModelCommonTrait;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use SkylarkSoft\GoRMG\SystemSettings\Models\User;

class HrMonthlyPaymentSummary extends Model
{

    use SoftDeletes,ModelCommonTrait;

    protected $table = 'hr_monthly_payment_summaries';

    protected $fillable = [
        'userid',
        'pay_month',
        'total_working_day',
        'total_weekend',
        'total_festival_day',
        'total_other_holiday',
        'total_holiday',
        'total_present_day',
        'total_absent_day',
        'total_late',
        'total_leave',
        'total_payable_days',
        'basic_salary',
        'house_rent',
        'medical_allowance',
        'transport_allowance',
        'food_allowance',
        'attendance_bonus',
        'gross_salary',
        'ot_hour_time',
        'ot_hour',
        'ot_minute',
        'ot_rate',
        'total_ot_amount',
        'total_regular_extra_ot_hour_time',
        'total_regular_extra_ot_hour',
        'total_regular_extra_ot_minute',
        'total_regular_unapproved_extra_ot_hour_time',
        'total_regular_unapproved_extra_ot_hour',
        'total_regular_unapproved_extra_ot_minute',
        'absent_deduction',
        'attendance_bonus_deduction',
        'revenue_stamp',
        'total_payable_amount',
        'already_paid_status',
        'pay_slip_generate_date',
        'salary_sheet_generate_date',
        'generated_by',
        'night_ot_hour_time',
        'night_ot_hour',
        'night_ot_minute',
        'night_ot_rate',
        'night_ot_amount',
        'total_night_unapproved_ot_hour_time',
        'total_night_unapproved_ot_hour',
        'total_night_unapproved_ot_minute',
        'factory_id',
        'created_by',
        'updated_by',
        'deleted_by'
    ];

    public function employeeOfficialInfo(): BelongsTo
    {
        return $this->belongsTo(HrEmployeeOfficialInfo::class, 'userid', 'unique_id')->withDefault();
    }

    public function employee(): BelongsTo
    {
        return $this->belongsTo(HrEmployee::class, 'userid', 'unique_id')->withDefault();
    }

    public function createdUser(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by')->withDefault();
    }

    public function updatedUser(): BelongsTo
    {
        return $this->belongsTo(User::class, 'updated_by')->withDefault();
    }

    public static function isSummeryGenerated($userId, $date) {
        return static::where('userid', $userId)
            ->whereMonth('pay_month', Carbon::parse($date)->format('m'))
            ->whereYear('pay_month', Carbon::parse($date)->format('Y'))
            ->count();
    }
}
