<?php

namespace SkylarkSoft\GoRMG\Subcontract\Models\SubTextileModels;

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
use SkylarkSoft\GoRMG\Subcontract\Services\SubDyeingStenteringServices;
use SkylarkSoft\GoRMG\SystemSettings\Models\Buyer;
use SkylarkSoft\GoRMG\SystemSettings\Models\Color;
use SkylarkSoft\GoRMG\SystemSettings\Models\Shift;

class SubDyeingStentering extends Model
{
    use SoftDeletes;
    use CommonModelTrait;
    use BelongsToFactory;

    protected $table = 'sub_dyeing_stenterings';
    protected $primaryKey = 'id';
    protected $fillable = [
        'sub_stentering_uid',
        'factory_id',
        'supplier_id',
        'entry_basis',
        'order_id',
        'order_no',
        'batch_id',
        'batch_no',
        'dyeing_unit_id',
        'shift_id',
        'machine_id',
        'production_date',
        'loading_date',
        'unloading_date',
        'machine_speed',
        'set_width',
        'out_width',
        'shrinkage',
        'pressure',
        'output_gsm',
        'temperature',
        'over_feed',
        'spirality',
        'under_feed',
        'remarks',
        'created_by',
        'updated_by',
        'deleted_by',
    ];

    protected $appends = [
        "entry_basis_value",
    ];

    protected const ENTRY_BASIS = [
        1 => 'BATCH',
        2 => 'ORDER',
    ];

    public function scopeSearch($query, Request $request)
    {
        $factoryId = $request->get('factory_id');
        $partyId = $request->get('party_id');
        $entryBasis = $request->get('entry_basis');
        $orderBatchNo = $request->get('order_batch_no');
        $dyeingUnit = $request->get('dyeing_unit');
        $productionDate = $request->get('production_date');
        $shift = $request->get('shift');
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
            ->when($shift, Filter::applyFilter('shift_id', $shift))
            ->when($productionDate, Filter::applyFilter('production_date', $productionDate))
            ->when($color, function ($query) use ($color) {
                $query->whereHas('subDyeingStenteringDetails', function ($q) use ($color) {
                    return $q->where('color_id', $color);
                });
            })->when($request->input('finish_qty'), function (Builder $query) use ($request) {
                return $query->whereHas('subDyeingStenteringDetails', function (Builder $query) use ($request) {
                    return $query->where('finish_qty', 'LIKE', "%{$request->input('finish_qty')}%");
                });
            });
    }

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

    public static function booted()
    {
        static::saving(function ($model) {
            if (! $model->id && in_array('created_by', $model->getFillable())) {
                $model->sub_stentering_uid = SubDyeingStenteringServices::generateUniqueId();
            }
        });
    }

    public function subDyeingStenteringDetails(): HasMany
    {
        return $this->hasMany(SubDyeingStenteringDetail::class, 'sub_stentering_id');
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

    public function machine(): BelongsTo
    {
        return $this->belongsTo(DyeingMachine::class, 'machine_id')->withDefault();
    }
}
