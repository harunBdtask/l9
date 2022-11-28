<?php

namespace SkylarkSoft\GoRMG\Subcontract\Models\Libraries;

use App\Models\BelongsToFactory;
use App\Models\CommonModelTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class SubDyeingRecipeOperation extends Model
{
    use SoftDeletes;
    use CommonModelTrait;
    use BelongsToFactory;

    protected $table = 'sub_dyeing_recipe_operations';
    protected $primaryKey = 'id';
    protected $fillable = [
        'factory_id',
        'name',
        'created_by',
        'updated_by',
        'deleted_by',
    ];
}
