<?php

namespace SkylarkSoft\GoRMG\SystemSettings\Models;

use App\ModelCommonTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class GarmentsItemGroup extends Model
{
    use SoftDeletes;
    use ModelCommonTrait;

    protected $fillable = [
        'factory_id',
        'name',
        'created_by',
        'updated_by',
        'deleted_by',
    ];
}
