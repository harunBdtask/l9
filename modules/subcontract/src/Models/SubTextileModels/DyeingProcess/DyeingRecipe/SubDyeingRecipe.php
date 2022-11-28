<?php

namespace SkylarkSoft\GoRMG\Subcontract\Models\SubTextileModels\DyeingProcess\DyeingRecipe;

use App\Models\BelongsToFactory;
use App\Models\CommonModelTrait;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Http\Request;
use SkylarkSoft\GoRMG\Subcontract\Filters\Filter;
use SkylarkSoft\GoRMG\Subcontract\Models\SubTextileModels\DyeingProcess\SubDyeingBatch;
use SkylarkSoft\GoRMG\Subcontract\Models\SubTextileModels\DyeingProcess\SubDyeingBatchMachineAllocation;
use SkylarkSoft\GoRMG\Subcontract\Services\SubTextileServices\SubTextileRecipeService;
use SkylarkSoft\GoRMG\SystemSettings\Models\Buyer;
use SkylarkSoft\GoRMG\SystemSettings\Models\Shift;

class SubDyeingRecipe extends Model
{
    use SoftDeletes;
    use CommonModelTrait;
    use BelongsToFactory;

    protected $table = 'sub_dyeing_recipes';
    protected $primaryKey = 'id';
    protected $fillable = [
        'recipe_uid',
        'factory_id',
        'supplier_id',
        'batch_no',
        'batch_id',
        'liquor_ratio',
        'total_liq_level',
        'shift_id',
        'recipe_date',
        'ld_no',
        'yarn_lot',
        'remarks',
        'created_by',
        'updated_by',
        'deleted_by',
    ];

    public static function booted()
    {
        static::saving(function ($model) {
            if (! $model->id && in_array('created_by', $model->getFillable())) {
                $model->recipe_uid = SubTextileRecipeService::generateUniqueId();
            }
        });
    }

    public function scopeSearch(Builder $query, Request $request)
    {
        $recipeUid = $request->input('recipe_uid');
        $recipeDate = $request->input('recipe_date');
        $factoryId = $request->input('factory_id');
        $supplierId = $request->input('supplier_id');
        $batchNo = $request->input('batch_no');
        $liquorRatio = $request->input('liquor_ratio');
        $totalLiqLevel = $request->input('total_liq_level');
        $shiftId = $request->input('shift_id');
        $totalBatchWeight = $request->input('total_batch_weight');
        $colorId = $request->input('color_id');

        $query->when($recipeUid, Filter::applyFilter('recipe_uid', $recipeUid))
            ->when($recipeDate, Filter::applyFilter('recipe_date', $recipeDate))
            ->when($factoryId, Filter::applyFilter('factory_id', $factoryId))
            ->when($supplierId, Filter::applyFilter('supplier_id', $supplierId))
            ->when($batchNo, Filter::applyFilter('batch_no', $batchNo))
            ->when($liquorRatio, Filter::applyFilter('liquor_ratio', $liquorRatio))
            ->when($totalLiqLevel, Filter::applyFilter('total_liq_level', $totalLiqLevel))
            ->when($shiftId, Filter::applyFilter('shift_id', $shiftId))
            ->when($totalBatchWeight, function (Builder $query) use ($totalBatchWeight) {
                $query->whereHas('subDyeingBatch', function (Builder $query) use ($totalBatchWeight) {
                    $query->where('total_batch_weight', $totalBatchWeight);
                });
            })
            ->when($colorId, function (Builder $query) use ($colorId) {
                $query->whereHas('subDyeingBatch', function (Builder $query) use ($colorId) {
                    return $query->whereHas('fabricColor', function (Builder $query) use ($colorId) {
                        return $query->where('fabric_color', $colorId);
                    });
                });
            });
    }

    public function supplier(): BelongsTo
    {
        return $this->belongsTo(Buyer::class, 'supplier_id')->withDefault();
    }

    public function subDyeingBatch(): BelongsTo
    {
        return $this->belongsTo(SubDyeingBatch::class, 'batch_id', 'id')->withDefault();
    }

    public function recipeDetails(): HasMany
    {
        return $this->hasMany(SubDyeingRecipeDetail::class, 'sub_dyeing_recipe_id', 'id');
    }

    public function Shift(): BelongsTo
    {
        return $this->belongsTo(Shift::class, 'shift_id', 'id')->withDefault();
    }

    public function recipeRequisitions(): HasMany
    {
        return $this->hasMany(SubDyeingRecipeRequisition::class, 'sub_dyeing_recipe_id', 'id');
    }

    public function machineAllocations(): HasMany
    {
        return $this->hasMany(SubDyeingBatchMachineAllocation::class, 'sub_dyeing_recipe_id', 'id');
    }
}
