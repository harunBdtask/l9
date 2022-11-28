<?php

namespace SkylarkSoft\GoRMG\SystemSettings\Models;

use App\ModelCommonTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class DyesChemicalStoreVariableSetting extends Model
{
    use SoftDeletes, ModelCommonTrait;

    protected $table = 'dyes_chemical_store_variable_settings';

    protected $fillable = [
        'factory_id',
        'approval_maintain',
        'created_by',
        'updated_by',
        'deleted_by',
    ];
}
