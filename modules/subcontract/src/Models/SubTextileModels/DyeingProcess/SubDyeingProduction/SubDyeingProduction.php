<?php

namespace SkylarkSoft\GoRMG\Subcontract\Models\SubTextileModels\DyeingProcess\SubDyeingProduction;

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
use SkylarkSoft\GoRMG\Subcontract\Models\SubTextileModels\SubTextileOrder;
use SkylarkSoft\GoRMG\Subcontract\Services\SubDyeingProductionService;
use SkylarkSoft\GoRMG\SystemSettings\Models\Buyer;
use SkylarkSoft\GoRMG\SystemSettings\Models\Shift;

class SubDyeingProduction extends Model
{
    use SoftDeletes;
    use CommonModelTrait;
    use BelongsToFactory;

    protected $table = 'sub_dyeing_productions';
    protected $primaryKey = 'id';
    protected $fillable = [
        'production_uid',
        'factory_id',
        'supplier_id',
        'order_id',
        'order_no',
        'batch_id',
        'batch_no',
        'production_date',
        'loading_date',
        'unloading_date',
        'shift_id',
        'tube',
        'remarks',
        'created_by',
        'updated_by',
        'deleted_by',
    ];

    public static function booted()
    {
        static::saving(function ($model) {
            if (! $model->id && in_array('created_by', $model->getFillable())) {
                $model->production_uid = SubDyeingProductionService::generateUniqueId();
            }
        });
    }

    public function scopeSearch($query, Request $request)
    {
        $productionDate = $request->get('production_date');
        $factoryId = $request->get('factory_id');
        $supplierId = $request->get('supplier_id');
        $subTextileOrderNo = $request->get('sub_textile_order_no');
        $subDyeingBatchNo = $request->get('sub_dyeing_batch_no');
        $machineId = $request->get('machine_id');
        $shiftId = $request->get('shift_id');
        $dyeingProductionQty = $request->get('dyeing_production_qty');

        return $query->when($productionDate, Filter::applyFilter('production_date', $productionDate))
            ->when($factoryId, Filter::applyFilter('factory_id', $factoryId))
            ->when($supplierId, Filter::applyFilter('supplier_id', $supplierId))
            ->when($subTextileOrderNo, Filter::applyFilter('order_no', $subTextileOrderNo))
            ->when($subDyeingBatchNo, Filter::applyFilter('batch_no', $subDyeingBatchNo))
            ->when($machineId, function (Builder $query) use ($machineId) {
                return $query->whereHas('subDyeingBatch', function (Builder $query) use ($machineId) {
                    return $query->whereHas('machineAllocations', function (Builder $query) use ($machineId) {
                        return $query->where('machine_id', $machineId);
                    });
                });
            })
            ->when($shiftId, Filter::applyFilter('shift_id', $shiftId))
            ->when($dyeingProductionQty, function (Builder $query) use ($dyeingProductionQty) {
                return $query->whereHas(
                    'subDyeingProductionDetails',
                    function (Builder $q) use ($dyeingProductionQty) {
                        return $q->where('dyeing_production_qty', $dyeingProductionQty);
                    }
                );
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

    public function subTextileOrder(): BelongsTo
    {
        return $this->belongsTo(SubTextileOrder::class, 'order_id', 'id')->withDefault();
    }

    public function subDyeingProductionDetails(): HasMany
    {
        return $this->hasMany(SubDyeingProductionDetail::class, 'sub_dyeing_production_id', 'id');
    }

    public function shift(): BelongsTo
    {
        return $this->belongsTo(Shift::class, 'shift_id')->withDefault();
    }
}
