<?php

namespace SkylarkSoft\GoRMG\Dyeing\Models\TextileModels\Compactor;

use App\Models\BelongsToBuyer;
use App\Models\BelongsToFactory;
use App\Models\CommonModelTrait;
use Carbon\Carbon;
use Illuminate\Http\Request;
use SkylarkSoft\GoRMG\Dyeing\Filters\Filter;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use SkylarkSoft\GoRMG\Dyeing\Models\TextileModels\DyeingBatch\DyeingBatch;
use SkylarkSoft\GoRMG\Dyeing\Models\TextileModels\TextileOrders\TextileOrder;
use SkylarkSoft\GoRMG\Dyeing\Services\TextileServices\UId\CompactorService;
use SkylarkSoft\GoRMG\Subcontract\Models\Libraries\SubDyeingUnit;
use SkylarkSoft\GoRMG\SystemSettings\Models\Shift;

class Compactor extends Model
{
    use SoftDeletes;
    use CommonModelTrait;
    use BelongsToFactory;
    use BelongsToBuyer;

    protected $table = 'compactors';
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
        "entry_basis_value"
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
            if (!$model->id && in_array('created_by', $model->getFillable())) {
                $model->unique_id = CompactorService::generateUniqueId();
            }
        });
    }

    public function textileOrder(): BelongsTo
    {
        return $this->belongsTo(TextileOrder::class, 'textile_order_id')->withDefault();
    }

    public function dyeingBatch(): BelongsTo
    {
        return $this->belongsTo(DyeingBatch::class, 'dyeing_batch_id')->withDefault();
    }

    public function dyeingUnit(): BelongsTo
    {
        return $this->belongsTo(SubDyeingUnit::class, 'dyeing_unit_id')->withDefault();
    }

    public function shift(): BelongsTo
    {
        return $this->belongsTo(Shift::class, 'shift_id')->withDefault();
    }

    public function compactorDetails(): HasMany
    {
        return $this->hasMany(CompactorDetail::class, 'compactor_id');
    }
    
    public function scopeSearch($query,Request $request)
    {
        $factoryId = $request->get('factory_id');
        $buyerId = $request->get('buyer_id');
        $order_no = $request->get('dyeing_order_no');
        $type = $request->input('type');

        $query->when($factoryId,Filter::applyFilter('factory_id',$factoryId))
            ->when($buyerId,Filter::applyFilter('buyer_id',$buyerId))
            ->when($order_no,Filter::applyFilter('textile_order_no',$order_no))
            ->when($type, function ($query) use ($type) {
                $query->whereHas('dyeingBatch.fabricSalesOrder', function ($q) use ($type) {
                    $q->where('booking_type', $type);
                });
            });
    }

}