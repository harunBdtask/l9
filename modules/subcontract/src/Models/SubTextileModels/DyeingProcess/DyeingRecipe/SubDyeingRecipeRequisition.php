<?php

namespace SkylarkSoft\GoRMG\Subcontract\Models\SubTextileModels\DyeingProcess\DyeingRecipe;

use App\Models\BelongsToFactory;
use App\Models\CommonModelTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Http\Request;
use SkylarkSoft\GoRMG\DyesStore\Models\DsStoreModel;
use SkylarkSoft\GoRMG\Subcontract\Services\SubTextileServices\SubTextileRecipeRequisitionService;

class SubDyeingRecipeRequisition extends Model
{
    use SoftDeletes;
    use CommonModelTrait;
    use BelongsToFactory;

    protected $table = 'sub_dyeing_recipe_requisitions';
    protected $primaryKey = 'id';
    protected $fillable = [
        'factory_id',
        'sub_dyeing_recipe_id',
        'requisition_uid',
        'store_id',
        'requisition_date',
        'created_by',
        'updated_by',
        'deleted_by',
    ];

    public function scopeSearch($query, Request $request)
    {
        $factory_id = $request->get('factory_id');
        $sub_dyeing_recipe_id = $request->get('sub_dyeing_recipe_id');
        $requisition_uid = $request->get('requisition_uid');
        $store_id = $request->get('store_id');
        $requisition_date = $request->get('requisition_date');

        return $query->when($factory_id, function ($q, $factory_id) {
            return $q->where('factory_id', $factory_id);
        })->when($sub_dyeing_recipe_id, function ($q, $sub_dyeing_recipe_id) {
            return $q->where('sub_dyeing_recipe_id', $sub_dyeing_recipe_id);
        })->when($requisition_uid, function ($q, $requisition_uid) {
            return $q->where('requisition_uid', 'LIKE', "%{$requisition_uid}%");
        })->when($store_id, function ($q, $store_id) {
            return $q->where('store_id', $store_id);
        })->when($requisition_date, function ($q, $requisition_date) {
            return $q->where('requisition_date', $requisition_date);
        });
    }

    public static function booted()
    {
        static::saving(function ($model) {
            if (! $model->id && in_array('created_by', $model->getFillable())) {
                $model->requisition_uid = SubTextileRecipeRequisitionService::generateUniqueId();
            }
        });
    }

    public function subDyeingRecipe(): BelongsTo
    {
        return $this->belongsTo(SubDyeingRecipe::class, 'sub_dyeing_recipe_id')->withDefault();
    }

    public function dsStore(): BelongsTo
    {
        return $this->belongsTo(DsStoreModel::class, 'store_id')->withDefault();
    }
}
