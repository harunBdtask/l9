<?php

namespace SkylarkSoft\GoRMG\Dyeing\Models\TextileModels\DyeingRecipe;

use App\Models\BelongsToFactory;
use App\Models\CommonModelTrait;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Http\Request;
use SkylarkSoft\GoRMG\Dyeing\Filters\Filter;
use SkylarkSoft\GoRMG\Dyeing\Services\TextileServices\UId\Recipes\DyeingRecipeRequisitionService;
use SkylarkSoft\GoRMG\DyesStore\Models\DsStoreModel;

class DyeingRecipeRequisition extends Model
{
    use SoftDeletes;
    use CommonModelTrait;
    use BelongsToFactory;

    protected $table = 'dyeing_recipe_requisitions';
    protected $primaryKey = 'id';
    protected $fillable = [
        'factory_id',
        'dyeing_recipe_id',
        'unique_id',
        'store_id',
        'requisition_date',
        'created_by',
        'updated_by',
        'deleted_by',
    ];

    public static function booted()
    {
        static::saving(function ($model) {
            if (!$model->id && in_array('created_by', $model->getFillable())) {
                $model->unique_id = DyeingRecipeRequisitionService::generateUniqueId();
            }
        });
    }

    public function scopeSearch(Builder $query, Request $request)
    {
        $factory_id = $request->get('factory_id');
        $dyeing_recipe_id = $request->get('dyeing_recipe_id');
        $unique_id = $request->get('unique_id');
        $store_id = $request->get('store_id');
        $requisition_date = $request->get('requisition_date');
        $type = $request->input('type');

        $query->when($factory_id, Filter::applyFilter('factory_id', $factory_id))
            ->when($dyeing_recipe_id, Filter::applyFilter('dyeing_recipe_id', $dyeing_recipe_id))
            ->when($unique_id, Filter::applyFilter('unique_id', $unique_id))
            ->when($store_id, Filter::applyFilter('store_id', $store_id))
            ->when($requisition_date, Filter::applyFilter('requisition_date', $requisition_date))
            ->when($type, function ($query) use ($type) {
                $query->whereHas('dyeingRecipe.subDyeingBatch.fabricSalesOrder', function ($q) use ($type) {
                    $q->where('booking_type', $type);
                });
            });
    }

    public function dyeingRecipe(): BelongsTo
    {
        return $this->belongsTo(DyeingRecipe::class, 'dyeing_recipe_id')
            ->withDefault();
    }

    public function store(): BelongsTo
    {
        return $this->belongsTo(DsStoreModel::class, 'store_id')
            ->withDefault();
    }

}
