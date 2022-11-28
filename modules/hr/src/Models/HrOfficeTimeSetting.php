<?php

namespace SkylarkSoft\GoRMG\HR\Models;

use App\ModelCommonTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HrOfficeTimeSetting extends Model
{
    use ModelCommonTrait;

    protected $table = 'hr_office_time_settings';

    protected $fillable = [
        'worker_office_time',
        'worker_late_allowed_minute',
        'staff_office_time',
        'staff_late_allowed_minute',
        'management_office_time',
        'management_late_allowed_minute',
    ];
}
