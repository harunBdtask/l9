<?php

namespace SkylarkSoft\GoRMG\HR\Models;

use App\ModelCommonTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class HrHoliday extends Model
{
    use SoftDeletes, ModelCommonTrait;

    protected $table = 'hr_holidays';

    protected $fillable = [
        'name',
        'date',
        'factory_id',
        'created_by',
        'updated_by',
        'deleted_by'
    ];
}
