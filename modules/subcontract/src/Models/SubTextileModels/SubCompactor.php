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
use SkylarkSoft\GoRMG\Subcontract\Services\SubCompactorService;
use SkylarkSoft\GoRMG\SystemSettings\Models\Buyer;
use SkylarkSoft\GoRMG\SystemSettings\Models\Shift;

class SubCompactor extends Model
{
    use SoftDeletes;
    use CommonModelTrait;
    use BelongsToFactory;

    protected $table = 'sub_compactors';
    protected $primaryKey = 'id';
    protected $fillable = [
        'sub_compactor_uid',
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
        'compaction',
        'output_gsm',
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
                $model->sub_compactor_uid = SubCompactorService::generateUniqueId();
            }
        });
    }

    public function scopeSearch(Builder $query, Request $request)
    {
        $query->when($request, function (Builder $query) use ($request) {
            $query->when(
                $request->input('production_date'),
                Filter::applyFilter('production_date', $request->input('production_date'))
            )->when(
                $request->input('factory_id'),
                Filter::applyFilter('factory_id', $request->input('factory_id'))
            )->when(
                $request->input('order_no'),
                Filter::applyFilter('order_no', $request->input('order_no'))
            )->when(
                $request->input('batch_no'),
                Filter::applyFilter('batch_no', $request->input('batch_no'))
            )->when(
                $request->input('shift_id'),
                Filter::applyFilter('shift_id', $request->input('shift_id'))
            )->when($request->input('finish_qty'), function (Builder $query) use ($request) {
                return $query->whereHas('subCompactorDetails', function (Builder $query) use ($request) {
                    return $query->where('finish_qty', 'LIKE', "%{$request->input('finish_qty')}%");
                });
            });
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

    public function subCompactorDetails(): HasMany
    {
        return $this->hasMany(SubCompactorDetail::class, 'sub_compactor_id');
    }

    public function machine(): BelongsTo
    {
        return $this->belongsTo(DyeingMachine::class, 'machine_id')->withDefault();
    }
}
