<?php

namespace SkylarkSoft\GoRMG\HR\Models;

use App\ModelCommonTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class HrSection extends Model
{
    use SoftDeletes,ModelCommonTrait;

    protected $table = 'hr_sections';
    protected $fillable = [
        'name',
        'name_bn',
        'department_id',
        'factory_id',
        'created_by',
        'updated_by',
        'deleted_by'
    ];

    public function department(): BelongsTo
    {
        return $this->belongsTo(HrDepartment::class, 'department_id');
    }
}
