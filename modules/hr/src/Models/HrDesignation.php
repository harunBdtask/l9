<?php

namespace SkylarkSoft\GoRMG\HR\Models;

use App\ModelCommonTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class HrDesignation extends Model
{
    use SoftDeletes, ModelCommonTrait;

    protected $table = 'hr_designations';

    protected $fillable = [
        'name',
        'name_bn',
        'factory_id',
        'created_by',
        'updated_by',
        'deleted_by'
    ];
}
