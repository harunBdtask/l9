<?php

namespace SkylarkSoft\GoRMG\HR\Models;

use App\ModelCommonTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class HrFastivalLeave extends Model
{
    use SoftDeletes, ModelCommonTrait;

    protected $table = 'hr_fastival_leaves';

    protected $fillable = [
        'name', 'leave_date',
        'factory_id',
        'created_by',
        'updated_by',
        'deleted_by'
    ];
}
