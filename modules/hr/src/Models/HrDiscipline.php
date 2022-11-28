<?php

namespace SkylarkSoft\GoRMG\HR\Models;

use App\ModelCommonTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class HrDiscipline extends Model
{
    use SoftDeletes, ModelCommonTrait;

    const ACTION_SUSPENDED = 'suspended';
    const ACTION_RELIEF = 'relief';
    const ACTION_TERMINATED = 'terminated';
    const ACTION_SALARY_DEDUCTION = 'salary_deduction';

    protected $table = 'hr_disciplines';

    protected $fillable = [
        'occurrence_date',
        'action_date',
        'occurrence_detail',
        'investigation_member',
        'investigation_detail',
        'report_date',
        'action_taken',
        'case_no',
        'factory_id',
        'created_by',
        'updated_by',
        'deleted_by'
    ];

    protected $dates = ['occurrence_date', 'action_date', 'report_date'];

    protected static function boot()
    {
        parent::boot();
        static::creating(function ($model) {
            $model->case_no = Str::random(8);
        });
    }

    public function details(): HasMany
    {
        return $this->hasMany(HrDisciplineDetail::class, 'discipline_id');
    }
}
