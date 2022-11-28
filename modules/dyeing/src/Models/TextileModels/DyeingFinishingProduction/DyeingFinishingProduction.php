<?php

namespace SkylarkSoft\GoRMG\Dyeing\Models\TextileModels\DyeingFinishingProduction;

use App\Models\BelongsToBuyer;
use App\Models\BelongsToFactory;
use App\Models\CommonModelTrait;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Http\Request;
use SkylarkSoft\GoRMG\Dyeing\Filters\Filter;
use SkylarkSoft\GoRMG\Dyeing\Models\TextileModels\DyeingBatch\DyeingBatch;
use SkylarkSoft\GoRMG\Dyeing\Models\TextileModels\TextileOrders\TextileOrder;
use SkylarkSoft\GoRMG\Dyeing\Services\TextileServices\UId\DyeingFinishingProductionService;
use SkylarkSoft\GoRMG\Subcontract\Models\Libraries\DyeingMachine;
use SkylarkSoft\GoRMG\Subcontract\Models\Libraries\SubDyeingUnit;
use SkylarkSoft\GoRMG\SystemSettings\Models\Buyer;
use SkylarkSoft\GoRMG\SystemSettings\Models\Shift;

class DyeingFinishingProduction extends Model
{
    use SoftDeletes;
    use CommonModelTrait;
    use BelongsToFactory;
    use BelongsToBuyer;

    protected $table = 'dyeing_finishing_productions';
    protected $primaryKey = 'id';
    protected $fillable = [
        'unique_id',
        'factory_id',
        'buyer_id',
        'entry_basis',
        'textile_order_id',
        'textile_order_no',
        'dyeing_batch_id',
        'dyeing_batch_no',
        'sub_dyeing_unit_id',
        'production_date',
        'machine_id',
        'loading_date',
        'unloading_date',
        'shift_id',
        'length_shrinkage',
        'width_shrinkage',
        'remarks',
        'created_by',
        'updated_by',
        'deleted_by',
    ];

    protected $appends = [
        'entry_basis_value',
    ];

    const ENTRY_BASIS = [
        1 => 'Batch Basis',
        2 => 'Order Basis',
    ];

    public function getEntryBasisValueAttribute(): string
    {
        return self::ENTRY_BASIS[$this->attributes['entry_basis']];
    }

    public function getLoadingDateAttribute(): string
    {
        return Carbon::create($this->attributes['loading_date'])->toDateTimeLocalString();
    }

    public function getUnloadingDateAttribute(): string
    {
        return Carbon::create($this->attributes['unloading_date'])->toDateTimeLocalString();
    }

    public static function booted()
    {
        static::saving(function ($model) {
            if (!$model->id && in_array('created_by', $model->getFillable())) {
                $model->unique_id = DyeingFinishingProductionService::generateUniqueId();
            }
        });
    }


    public function scopeSearch($query,Request $request)
    {
        $production_date = $request->get('production_date');
        $factoryId = $request->get('factory_id');
        $buyerId = $request->get('buyer_id');
        $order_no = $request->get('dyeing_order_no');
        $batch_no = $request->get('dyeing_batch_no');
        $type = $request->input('type');

        $query->when($production_date,Filter::applyFilter('production_date',$production_date))
            ->when($factoryId,Filter::applyFilter('factory_id',$factoryId))
            ->when($buyerId,Filter::applyFilter('buyer_id',$buyerId))
            ->when($order_no,Filter::applyFilter('textile_order_no',$order_no))
            ->when($batch_no,Filter::applyFilter('dyeing_batch_no',$batch_no))
            ->when($type, function ($query) use ($type) {
                $query->whereHas('dyeingBatch.fabricSalesOrder', function ($q) use ($type) {
                    $q->where('booking_type', $type);
                });
            });
    }

    /*------------------------------------------------ Start Relations -----------------------------------------------*/

    public function buyer(): BelongsTo
    {
        return $this->belongsTo(Buyer::class, 'buyer_id')->withDefault();
    }

    public function dyeingBatch(): BelongsTo
    {
        return $this->belongsTo(DyeingBatch::class, 'dyeing_batch_id', 'id')
            ->withDefault();
    }

    public function textileOrder(): BelongsTo
    {
        return $this->belongsTo(TextileOrder::class, 'textile_order_id', 'id')
            ->withDefault();
    }

    public function shift(): BelongsTo
    {
        return $this->belongsTo(Shift::class, 'shift_id')->withDefault();
    }

    public function finishingProductionDetails(): HasMany
    {
        return $this->hasMany(
            DyeingFinishingProductionDetail::class,
            'dyeing_finishing_production_id',
            'id'
        );
    }

    public function subDyeingUnit(): BelongsTo
    {
        return $this->belongsTo(SubDyeingUnit::class, 'sub_dyeing_unit_id')->withDefault();
    }

    public function machine(): BelongsTo
    {
        return $this->belongsTo(DyeingMachine::class, 'machine_id')->withDefault();
    }

    /*------------------------------------------------- End Relations ------------------------------------------------*/

}
