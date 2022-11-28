<?php

namespace SkylarkSoft\GoRMG\Subcontract\Models\Libraries;

use App\Models\BelongsToFactory;
use App\Models\CommonModelTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class SubDyeingOperationFunction extends Model
{
    use SoftDeletes;
    use CommonModelTrait;
    use BelongsToFactory;

    protected $table = 'sub_dyeing_operation_functions';
    protected $primaryKey = 'id';
    protected $fillable = [
        'factory_id',
        'dyeing_recipe_operation_id',
        'function_name',
        'created_by',
        'updated_by',
        'deleted_by',
    ];

    public function dyeingRecipeOperation(): BelongsTo
    {
        return $this->belongsTo(SubDyeingRecipeOperation::class, 'dyeing_recipe_operation_id')
            ->withDefault();
    }
}
