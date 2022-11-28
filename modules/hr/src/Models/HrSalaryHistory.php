<?php

namespace SkylarkSoft\GoRMG\HR\Models;

use App\ModelCommonTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class HrSalaryHistory extends Model
{
    use SoftDeletes,ModelCommonTrait;

    protected $table = 'hr_salary_histories';

    protected $fillable = [
        'employee_id',
        'designation_id',
        'department_id',
        'year',
        'gross_salary',
        'factory_id',
        'created_by',
        'updated_by',
        'deleted_by'
    ];

    public function employee(): BelongsTo
    {
        return $this->belongsTo(HrEmployee::class, 'employee_id')->withDefault();
    }

    public function department(): BelongsTo
    {
        return $this->belongsTo(HrDepartment::class, 'department_id')->withDefault();
    }

    public function designation(): BelongsTo
    {
        return $this->belongsTo(HrDesignation::class, 'designation_id')->withDefault();
    }
}
