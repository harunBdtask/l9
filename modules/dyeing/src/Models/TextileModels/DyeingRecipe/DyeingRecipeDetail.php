<?php

namespace SkylarkSoft\GoRMG\Dyeing\Models\TextileModels\DyeingRecipe;

use App\Models\CommonModelTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use SkylarkSoft\GoRMG\DyesStore\Models\DsUom;
use SkylarkSoft\GoRMG\DyesStore\Models\DsItem;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use SkylarkSoft\GoRMG\Subcontract\Models\Libraries\SubDyeingRecipeOperation;
use SkylarkSoft\GoRMG\Subcontract\Models\Libraries\SubDyeingOperationFunction;

class DyeingRecipeDetail extends Model
{
    use SoftDeletes;
    use CommonModelTrait;

    protected $table = 'dyeing_recipe_details';
    protected $primaryKey = 'id';
    protected $fillable = [
        'dyeing_recipe_id',
        'recipe_operation_id',
        'recipe_function_id',
        'item_id',
        'unit_of_measurement_id',
        'percentage',
        'g_per_ltr',
        'plus_minus',
        'additional',
        'total_qty',
        'remarks',
        'created_by',
        'updated_by',
        'deleted_by',
        'deleted_at',
    ];

    public function recipe(): BelongsTo
    {
        return $this->belongsTo(DyeingRecipe::class, 'dyeing_recipe_id', 'id');
    }

    public function recipeOperation(): BelongsTo
    {
        return $this->belongsTo(SubDyeingRecipeOperation::class, 'recipe_operation_id', 'id');
    }

    public function recipeFunction(): BelongsTo
    {
        return $this->belongsTo(SubDyeingOperationFunction::class, 'recipe_function_id', 'id');
    }

    public function unitOfMeasurement(): BelongsTo
    {
        return $this->belongsTo(DsUom::class, 'unit_of_measurement_id')->withDefault();
    }

    public function item(): BelongsTo
    {
        return $this->belongsTo(DsItem::class, 'item_id')->withDefault();
    }

}
