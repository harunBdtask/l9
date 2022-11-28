<?php

namespace SkylarkSoft\GoRMG\Dyeing\Models\TextileModels\DyeingRecipe;

use App\Models\BelongsToBuyer;
use App\Models\BelongsToFactory;
use App\Models\CommonModelTrait;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Http\Request;
use SkylarkSoft\GoRMG\Dyeing\Filters\Filter;
use SkylarkSoft\GoRMG\SystemSettings\Models\Shift;
use Illuminate\Database\Eloquent\Relations\HasMany;
use SkylarkSoft\GoRMG\SystemSettings\Models\Buyer;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use SkylarkSoft\GoRMG\Dyeing\Models\TextileModels\DyeingBatch\DyeingBatch;
use SkylarkSoft\GoRMG\Dyeing\Services\TextileServices\UId\Recipes\DyeingRecipeService;

class DyeingRecipe extends Model
{
    use SoftDeletes;
    use CommonModelTrait;
    use BelongsToFactory;
    use BelongsToBuyer;

    protected $table = 'dyeing_recipes';
    protected $primaryKey = 'id';
    protected $fillable = [
        'unique_id',
        'factory_id',
        'buyer_id',
        'dyeing_batch_no',
        'dyeing_batch_id',
        'liquor_ratio',
        'total_liq_level',
        'shift_id',
        'recipe_date',
        'yarn_lot',
        'remarks',
        'created_by',
        'updated_by',
        'deleted_by',
    ];

    public static function booted()
    {
        static::saving(function ($model) {
            if (!$model->id && in_array('created_by', $model->getFillable())) {
                $model->unique_id = DyeingRecipeService::generateUniqueId();
            }
        });
    }

    public function scopeSearch(Builder $query, Request $request)
    {
        $query->when($request, function (Builder $query) use ($request) {

            $factoryId = $request->input('factory_id');
            $uniqueId = $request->input('unique_id');
            $buyerId = $request->input('buyer_id');
            $batchNo = $request->input('dyeing_batch_no');
            $liquorRatio = $request->input('liquor_ratio');
            $totalLiqLevel = $request->input('total_liq_level');
            $shiftId = $request->input('shift_id');
            $recipeDate = $request->input('recipe_date');
            $type = $request->input('type');

            $query->when($factoryId, Filter::applyFilter('factory_id', $factoryId))
                ->when($uniqueId, Filter::applyFilter('unique_id', $uniqueId))
                ->when($buyerId, Filter::applyFilter('buyer_id', $buyerId))
                ->when($batchNo, Filter::applyFilter('dyeing_batch_no', $batchNo))
                ->when($liquorRatio, Filter::applyFilter('liquor_ratio', $liquorRatio))
                ->when($totalLiqLevel, Filter::applyFilter('total_liq_level', $totalLiqLevel))
                ->when($shiftId, Filter::applyFilter('shift_id', $shiftId))
                ->when($recipeDate, Filter::applyFilter('recipe_date', $recipeDate))
                ->when($type, function ($query) use ($type) {
                    $query->whereHas('subDyeingBatch.fabricSalesOrder', function ($q) use ($type) {
                        $q->where('booking_type', $type);
                    });
                });
        });
    }

    public function subDyeingBatch(): BelongsTo
    {
        return $this->belongsTo(DyeingBatch::class, 'dyeing_batch_id', 'id')
            ->withDefault();
    }

    public function recipeDetails(): HasMany
    {
        return $this->hasMany(DyeingRecipeDetail::class, 'dyeing_recipe_id', 'id');
    }

    public function Shift(): BelongsTo
    {
        return $this->belongsTo(Shift::class, 'shift_id', 'id')
            ->withDefault();
    }

    public function recipeRequisitions(): HasMany
    {
        return $this->hasMany(DyeingRecipeRequisition::class, 'dyeing_recipe_id', 'id');
    }

    public function buyer(): BelongsTo
    {
        return $this->belongsTo(Buyer::class, 'buyer_id')->withDefault();
    }

}
