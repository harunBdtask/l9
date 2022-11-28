<?php

namespace SkylarkSoft\GoRMG\Subcontract\Models\SubTextileModels\DyeingProcess;

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
use SkylarkSoft\GoRMG\Subcontract\Models\SubTextileModels\SubTextileOrder;
use SkylarkSoft\GoRMG\Subcontract\Services\SubDyeingHtSetService;
use SkylarkSoft\GoRMG\SystemSettings\Models\Buyer;
use SkylarkSoft\GoRMG\SystemSettings\Models\Color;
use SkylarkSoft\GoRMG\SystemSettings\Models\Shift;

class SubDyeingHtSet extends Model
{
    use SoftDeletes;
    use CommonModelTrait;
    use BelongsToFactory;

    protected $table = "sub_dyeing_ht_sets";
    protected $primaryKey = "id";
    protected $fillable = [
        "ht_set_uid",
        "factory_id",
        "supplier_id",
        "entry_basis",
        "order_id",
        "order_no",
        "batch_id",
        "batch_no",
        "dyeing_unit_id",
        "shift_id",
        "machine_id",
        "production_date",
        "loading_date",
        "unloading_date",
        "remarks",
    ];

    protected $appends = [
        "entry_basis_value",
    ];

    protected const ENTRY_BASIS = [
        1 => 'BATCH',
        2 => 'ORDER',
    ];

    public function getEntryBasisValueAttribute(): ?string
    {
        return self::ENTRY_BASIS[$this->attributes['entry_basis']] ?? null;
    }

    public function getLoadingDateAttribute(): string
    {
        return Carbon::create($this->attributes['loading_date'])->toDateTimeLocalString();
    }

    public function getUnloadingDateAttribute(): string
    {
        return Carbon::create($this->attributes['unloading_date'])->toDateTimeLocalString();
    }

    public function scopeSearch($query, Request $request)
    {
        $factoryId = $request->get('factory_id');
        $partyId = $request->get('party_id');
        $entryBasis = $request->get('entry_basis');
        $orderBatchNo = $request->get('order_batch_no');
        $dyeingUnit = $request->get('dyeing_unit');
        $productionDate = $request->get('production_date');
        $machine = $request->get('machine');
        $shift = $request->get('shift');
        $loadingTime = $request->get('loading_time');
        $color = $request->get('color');

        if ($color) {
            $color = Color::query()->where('name', 'LIKE', "%{$color}%")->first()->id;
        }

        return $query->when($factoryId, Filter::applyFilter('factory_id', $factoryId))
            ->when($partyId, Filter::applyFilter('supplier_id', $partyId))
            ->when($entryBasis, Filter::applyFilter('entry_basis', $entryBasis))
            ->when($orderBatchNo, function ($query, $orderBatchNo) {
                $query->where('order_no', 'LIKE', "%{$orderBatchNo}%")
                    ->orWhere('batch_no', 'LIKE', "%{$orderBatchNo}%");
            })
            ->when($dyeingUnit, Filter::applyFilter('dyeing_unit_id', $dyeingUnit))
            ->when($machine, Filter::applyFilter('machine_id', $machine))
            ->when($shift, Filter::applyFilter('shift_id', $shift))
            ->when($productionDate, Filter::applyFilter('production_date', $productionDate))
            ->when($loadingTime, Filter::applyFilter('loading_date', $loadingTime))
            ->when($color, function ($query) use ($color) {
                $query->whereHas('subDyeingHtSetDetail', function ($q) use ($color) {
                    return $q->where('color_id', $color);
                });
            })->when($request->input('finish_qty'), function (Builder $query) use ($request) {
                return $query->whereHas('subDyeingHtSetDetail', function (Builder $query) use ($request) {
                    return $query->where('finish_qty', 'LIKE', "%{$request->input('finish_qty')}%");
                });
            });
    }

    public static function booted()
    {
        static::saving(function ($model) {
            $model->ht_set_uid = SubDyeingHtSetService::generateUniqueId();
        });
    }

    public function supplier(): BelongsTo
    {
        return $this->belongsTo(Buyer::class, 'supplier_id')->withDefault();
    }

    public function subTextileOrder(): BelongsTo
    {
        return $this->belongsTo(SubTextileOrder::class, 'order_id')->withDefault();
    }

    public function subDyeingBatch(): BelongsTo
    {
        return $this->belongsTo(SubDyeingBatch::class, 'batch_id')->withDefault();
    }

    public function subDyeingUnit(): BelongsTo
    {
        return $this->belongsTo(SubDyeingUnit::class, 'dyeing_unit_id')->withDefault();
    }

    public function shift(): BelongsTo
    {
        return $this->belongsTo(Shift::class, 'shift_id')->withDefault();
    }

    public function subDyeingHtSetDetail(): HasMany
    {
        return $this->hasMany(SubDyeingHtSetDetail::class, 'sub_dyeing_ht_set_id');
    }

    public function machine(): BelongsTo
    {
        return $this->belongsTo(DyeingMachine::class, 'machine_id')->withDefault();
    }
}
