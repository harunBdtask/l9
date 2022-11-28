<?php

namespace SkylarkSoft\GoRMG\HR\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;
use SkylarkSoft\GoRMG\SystemSettings\Models\User;
use App\Casts\Json;

class HrAttendanceRawData extends Model
{
    use SoftDeletes;

    protected $table = 'hr_attendance_raw_data';
    protected $fillable = [
        'userid',
        'punch_time',
        'attendance_date',
        'flag',
        'machine_data',
        'factory_id',
        'created_by',
        'updated_by',
        'deleted_by'
    ];

    protected $casts = [
        'machine_data' => Json::class,
    ];

    /*
     * NB::  DO NOT USE BOOT METHOD TO UPDATE CREATED_BY AND UPDATED_BY COLUMN
     *
     */

    public function employeeOfficialInfo(): BelongsTo
    {
        return $this->belongsTo(HrEmployeeOfficialInfo::class, 'userid', 'punch_card_id')->withDefault();
    }

    public function createdUser(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by')->withDefault();
    }

    public function updatedUser(): BelongsTo
    {
        return $this->belongsTo(User::class, 'updated_by')->withDefault();
    }

}
