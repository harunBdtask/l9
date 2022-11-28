<?php

namespace SkylarkSoft\GoRMG\Subcontract\Models\SubTextileModels\SubDyeingTumble;

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
use SkylarkSoft\GoRMG\Subcontract\Services\SubTextileServices\UId\SubDyeingTumbleService;
use SkylarkSoft\GoRMG\SystemSettings\Models\Buyer;
use SkylarkSoft\GoRMG\SystemSettings\Models\Shift;

class SubDyeingTumble extends Model
{
    use SoftDeletes;
    use CommonModelTrait;
    use BelongsToFactory;

    protected $table = 'sub_dyeing_tumbles';
    protected $primaryKey = 'id';
    protected $fillable = [
        'tumble_uid',
        'factory_id',
        'supplier_id',
        'entry_basis',
        'sub_dyeing_batch_id',
        'sub_dyeing_batch_no',
        'sub_textile_order_id',
        'sub_textile_order_no',
        'production_date',
        'streaming_date',
        'shift_id',
        'dry_date',
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

    public static function booted()
    {
        static::saving(function ($model) {
            if (! $model->id && in_array('created_by', $model->getFillable())) {
                $model->tumble_uid = SubDyeingTumbleService::generateUniqueId();
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
                $request->input('sub_textile_order_no'),
                Filter::applyFilter('sub_textile_order_no', $request->input('sub_textile_order_no'))
            )->when(
                $request->input('sub_dyeing_batch_no'),
                Filter::applyFilter('sub_dyeing_batch_no', $request->input('sub_dyeing_batch_no'))
            )->when(
                $request->input('shift_id'),
                Filter::applyFilter('shift_id', $request->input('shift_id'))
            )->when($request->input('color_id'), function (Builder $query) use ($request) {
                $query->whereHas(
                    'tumbleDetails',
                    Filter::applyFilter('color_id', $request->input('color_id'))
                );
            })->when($request->input('finish_qty'), function (Builder $query) use ($request) {
                return $query->whereHas('tumbleDetails', function (Builder $query) use ($request) {
                    return $query->where('finish_qty', 'LIKE', "%{$request->input('finish_qty')}%");
                });
            });
        });
    }

    /*------------------------------------------------ Start Relations -----------------------------------------------*/

    public function subDyeingBatch(): BelongsTo
    {
        return $this->belongsTo(SubDyeingBatch::class, 'sub_dyeing_batch_id', 'id')
            ->withDefault();
    }

    public function subTextileOrder(): BelongsTo
    {
        return $this->belongsTo(SubTextileOrder::class, 'sub_textile_order_id', 'id')
            ->withDefault();
    }

    public function shift(): BelongsTo
    {
        return $this->belongsTo(Shift::class, 'shift_id')->withDefault();
    }

    public function tumbleDetails(): HasMany
    {
        return $this->hasMany(SubDyeingTumbleDetail::class, 'sub_dyeing_tumble_id', 'id');
    }

    public function supplier(): BelongsTo
    {
        return $this->belongsTo(Buyer::class, 'supplier_id')->withDefault();
    }

    /*------------------------------------------------- End Relations ------------------------------------------------*/
}
