<?php

namespace SkylarkSoft\GoRMG\HR\Models;

use App\ModelCommonTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class HrShift extends Model
{
    use SoftDeletes,ModelCommonTrait;

    protected $table = 'hr_shifts';

    protected $fillable = [
        'name',
        'start_time',
        'end_time',
        'factory_id',
        'created_by',
        'updated_by',
        'deleted_by'
    ];
}
