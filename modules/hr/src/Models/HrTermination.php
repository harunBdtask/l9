<?php

namespace SkylarkSoft\GoRMG\HR\Models;


use App\ModelCommonTrait;
use Closure;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class HrTermination extends Model
{
    use SoftDeletes, ModelCommonTrait;

    protected $table = 'hr_terminations';

    protected $fillable = [
        'employee_id',
        'termination_date',
        'termination_reason',
        'last_working_day',
        'factory_id',
        'created_by',
        'updated_by',
        'deleted_by'
    ];

    protected static function boot()
    {
        parent::boot();
        static::creating(Closure::fromCallable([__CLASS__, 'terminationObserver']));
        static::updating(Closure::fromCallable([__CLASS__, 'terminationObserver']));
    }

    public static function terminationObserver($model)
    {
        $info = HrEmployeeOfficialInfo::where('employee_id', $model->employee_id)->first();
        if ($info) {
            $info->termination_status = HrEmployeeOfficialInfo::TERMINATED;
            $info->termination_date = $model->termination_date;
            $info->save();
        }
    }
}
