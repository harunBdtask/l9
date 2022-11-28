<?php

namespace SkylarkSoft\GoRMG\SystemSettings\Models;

use App\ModelCommonTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class YarnStoreVariableSetting extends Model
{
    use SoftDeletes, ModelCommonTrait;

    protected $table = 'yarn_store_variable_settings';

    protected $fillable = [
        'factory_id',
        'approval_maintain',
        'created_by',
        'updated_by',
        'deleted_by',
    ];
}
