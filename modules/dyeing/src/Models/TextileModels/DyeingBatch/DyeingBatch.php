<?php

namespace SkylarkSoft\GoRMG\Dyeing\Models\TextileModels\DyeingBatch;

use App\Casts\Json;
use App\Models\BelongsToBuyer;
use App\Models\BelongsToFactory;
use App\Models\CommonModelTrait;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Http\Request;
use SkylarkSoft\GoRMG\Dyeing\Filters\Filter;
use SkylarkSoft\GoRMG\Dyeing\Services\TextileServices\UId\DyeingBatchService;
use SkylarkSoft\GoRMG\Knitting\Models\FabricSalesOrder;
use SkylarkSoft\GoRMG\Merchandising\Services\FabricDescriptionService;
use SkylarkSoft\GoRMG\Subcontract\Models\Libraries\SubDyeingUnit;
use SkylarkSoft\GoRMG\SystemSettings\Models\Color;
use SkylarkSoft\GoRMG\SystemSettings\Models\CompositionType;
use SkylarkSoft\GoRMG\SystemSettings\Models\NewFabricComposition;
use SkylarkSoft\GoRMG\SystemSettings\Services\DiaTypesService;

class DyeingBatch extends Model
{
    use SoftDeletes;
    use CommonModelTrait;
    use BelongsToFactory;
    use BelongsToBuyer;

    protected $table = 'dyeing_batches';
    protected $primaryKey = 'id';
    protected $fillable = [
        'unique_id',
        'batch_no',
        'factory_id',
        'buyer_id',
        'sub_dyeing_unit_id',
        'textile_orders_id',
        'orders_no',
        'batch_date',
        'color_range_id',
        'fabric_composition_id',
        'fabric_type_id',
        'color_id',
        'color_type_id',
        'dia_type_id',
        'gsm',
        'fabric_description',
        'total_batch_weight',
        'total_machine_capacity',
        'fabric_color_id',
        'ld_no',
        'process_loss',
        'finish_dia',
        'remarks',
        'created_by',
        'updated_by',
        'deleted_by',
        'sales_order_id',
    ];

    protected $casts = [
        'textile_orders_id' => Json::class,
        'orders_no' => 'array',
    ];

    protected $appends = [
        'dia_type_value',
        'fabric_composition_value',
    ];

    public function getDiaTypeValueAttribute()
    {
        return isset($this->attributes['dia_type_id'])
            ? DiaTypesService::get($this->attributes['dia_type_id'])['name']
            : null;
    }

    public function getFabricCompositionValueAttribute(): ?string
    {
        return isset($this->attributes['fabric_composition_id']) ?
            FabricDescriptionService::description($this->attributes['fabric_composition_id']) : null;
    }

    public function scopeSearch(Builder $query, Request $request)
    {
        $query->when($request, function (Builder $query) use ($request) {

            $factoryId = $request->input('factory_id');
            $uniqueId = $request->input('unique_id');
            $buyerId = $request->input('buyer_id');
            $textileOrderNo = $request->input('order_no');
            $batchNo = $request->input('batch_no');
            $fabricColorId = $request->input('fabric_color_id');
            $totalBatchWeight = $request->input('total_batch_weight');
            $batchDate = $request->input('batch_date');
            $type = $request->input('type');

            $query->when($factoryId, Filter::applyFilter('factory_id', $factoryId))
                ->when($uniqueId, Filter::applyFilter('unique_id', $uniqueId))
                ->when($buyerId, Filter::applyFilter('buyer_id', $buyerId))
                ->when($textileOrderNo, Filter::applyJsonContainsFilter('orders_no', $textileOrderNo))
                ->when($batchNo, Filter::applyFilter('batch_no', $batchNo))
                ->when($fabricColorId, Filter::applyFilter('fabric_color_id', $fabricColorId))
                ->when($totalBatchWeight, Filter::applyFilter('total_batch_weight', $totalBatchWeight))
                ->when($batchDate, Filter::applyFilter('batch_date', $batchDate))
                ->when($type, function ($query) use ($type) {
                    $query->whereHas('fabricSalesOrder', function ($q) use ($type) {
                        $q->where('booking_type', $type);
                    });
                });
        });
    }

    public static function boot()
    {
        parent::boot();

        static::saving(function ($model) {
            if (!$model->id && in_array('created_by', $model->getFillable())) {
                $model->unique_id = DyeingBatchService::generateUniqueId();
            }
        });
    }

    public function dyeingBatchDetails(): HasMany
    {
        return $this->hasMany(DyeingBatchDetail::class, 'dyeing_batch_id', 'id');
    }

    public function fabricComposition(): BelongsTo
    {
        return $this->belongsTo(NewFabricComposition::class, 'fabric_composition_id')
            ->withDefault();
    }

    public function fabricType(): BelongsTo
    {
        return $this->belongsTo(CompositionType::class, 'fabric_type_id')->withDefault();
    }

    public function color(): BelongsTo
    {
        return $this->belongsTo(Color::class, 'color_id')->withDefault();
    }

    public function fabricColor(): BelongsTo
    {
        return $this->belongsTo(Color::class, 'fabric_color_id')->withDefault();
    }

    public function machineAllocations(): HasMany
    {
        return $this->hasMany(DyeingBatchMachineAllocation::class, 'dyeing_batch_id', 'id');
    }

    public function subDyeingUnit(): BelongsTo
    {
        return $this->belongsTo(SubDyeingUnit::class, 'sub_dyeing_unit_id')
            ->withDefault();
    }

    public function fabricSalesOrder(): BelongsTo
    {
        return $this->belongsTo(FabricSalesOrder::class, 'sales_order_id')
            ->withDefault();
    }

}
