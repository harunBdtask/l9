<?php

namespace SkylarkSoft\GoRMG\HR\Models;

use App\ModelCommonTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class HrDepartment extends Model
{
    use SoftDeletes, ModelCommonTrait;

    protected $table = "hr_departments";

    protected $fillable = [
        'name',
        'name_bn',
        'factory_id',
        'created_by',
        'updated_by',
        'deleted_by'
    ];

    public function sections(): HasMany
    {
        return $this->hasMany(HrSection::class, 'department_id');
    }
}
