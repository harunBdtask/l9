<?php

namespace SkylarkSoft\GoRMG\HR\Models;

use App\ModelCommonTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class HrBank extends Model
{
    use SoftDeletes, ModelCommonTrait;

    protected $table = 'hr_banks';

    protected $fillable = [
        'name',
        'branch',
        'address',
        'factory_id',
        'created_by',
        'updated_by',
        'deleted_by'
    ];
}
