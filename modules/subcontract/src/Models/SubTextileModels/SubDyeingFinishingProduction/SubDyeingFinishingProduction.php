<?php

namespace SkylarkSoft\GoRMG\Subcontract\Models\SubTextileModels\SubDyeingFinishingProduction;

use App\Models\BelongsToFactory;
use App\Models\CommonModelTrait;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Http\Request;
use SkylarkSoft\GoRMG\Subcontract\Filters\Filter;
use SkylarkSoft\GoRMG\Subcontract\Models\Libraries\DyeingMachine;
use SkylarkSoft\GoRMG\Subcontract\Models\Libraries\SubDyeingUnit;
use SkylarkSoft\GoRMG\Subcontract\Models\SubTextileModels\DyeingProcess\SubDyeingBatch;
use SkylarkSoft\GoRMG\Subcontract\Models\SubTextileModels\SubTextileOrder;
use SkylarkSoft\GoRMG\Subcontract\Services\SubTextileServices\UId\SubDyeingFinishingProductionService;
use SkylarkSoft\GoRMG\SystemSettings\Models\Buyer;
use SkylarkSoft\GoRMG\SystemSettings\Models\Shift;

class SubDyeingFinishingProduction extends Model
{
    use SoftDeletes;
    use CommonModelTrait;
    use BelongsToFactory;

    protected $table = 'sub_dyeing_finishing_productions';
    protected $primaryKey = 'id';
    protected $fillable = [
        'production_uid',
        'factory_id',
        'supplier_id',
        'entry_basis',
        'sub_dyeing_batch_id',
        'sub_dyeing_batch_no',
        'sub_textile_order_id',
        'sub_textile_order_no',
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
            if (! $model->id && in_array('created_by', $model->getFillable())) {
                $model->production_uid = SubDyeingFinishingProductionService::generateUniqueId();
            }
        });
    }

    public function scopeSearch(Builder $query, Request $request)
    {
        $query->when($request, function (Builder $query) use ($request) {
            $query->when(
                $request->input('entry_basis'),
                Filter::applyFilter('entry_basis', $request->input('entry_basis'))
            )->when(
                $request->input('production_date'),
                Filter::applyFilter('production_date', $request->input('production_date'))
            )->when(
                $request->input('factory_id'),
                Filter::applyFilter('factory_id', $request->input('factory_id'))
            )->when(
                $request->input('supplier_id'),
                Filter::applyFilter('supplier_id', $request->input('supplier_id'))
            )->when(
                $request->input('sub_textile_order_no'),
                Filter::applyFilter('sub_textile_order_no', $request->input('sub_textile_order_no'))
            )->when(
                $request->input('sub_dyeing_batch_no'),
                Filter::applyFilter('sub_dyeing_batch_no', $request->input('sub_dyeing_batch_no'))
            )->when(
                $request->input('machine_id'),
                Filter::applyFilter('machine_id', $request->input('machine_id'))
            )->when(
                $request->input('shift_id'),
                Filter::applyFilter('shift_id', $request->input('shift_id'))
            )->when($request->input('color_id'), function (Builder $query) use ($request) {
                $query->whereHas(
                    'finishingProductionDetails',
                    Filter::applyFilter('color_id', $request->input('color_id'))
                );
            })->when($request->input('finish_qty'), function (Builder $query) use ($request) {
                return $query->whereHas('finishingProductionDetails', function (Builder $query) use ($request) {
                    return $query->where('finish_qty', 'LIKE', "%{$request->input('finish_qty')}%");
                });
            });
        });
    }

    /*------------------------------------------------ Start Relations -----------------------------------------------*/

    public function supplier(): BelongsTo
    {
        return $this->belongsTo(Buyer::class, 'supplier_id')->withDefault();
    }

    public function subDyeingBatch(): BelongsTo
    {
        return $this->belongsTo(SubDyeingBatch::class, 'sub_dyeing_batch_id', 'id')->withDefault();
    }

    public function subTextileOrder(): BelongsTo
    {
        return $this->belongsTo(SubTextileOrder::class, 'sub_textile_order_id', 'id')->withDefault();
    }

    public function shift(): BelongsTo
    {
        return $this->belongsTo(Shift::class, 'shift_id')->withDefault();
    }

    public function finishingProductionDetails(): HasMany
    {
        return $this->hasMany(
            SubDyeingFinishingProductionDetail::class,
            'sub_dyeing_finishing_production_id',
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
