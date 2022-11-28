<?php

namespace SkylarkSoft\GoRMG\HR\Models;

use App\ModelCommonTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class HrGrade extends Model
{
    use SoftDeletes, ModelCommonTrait;

    protected $table = 'hr_grades';
    protected $fillable = [
        'name',
        'name_bn',
        'group_id',
        'basic_salary',
        'home_rent',
        'total_salary',
        'factory_id',
        'created_by',
        'updated_by',
        'deleted_by'
    ];

    public function group(): BelongsTo
    {
        return $this->belongsTo(HrGroup::class, 'group_id')->withDefault();
    }
}
