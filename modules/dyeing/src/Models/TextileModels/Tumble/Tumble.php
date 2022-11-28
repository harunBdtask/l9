<?php

namespace SkylarkSoft\GoRMG\Dyeing\Models\TextileModels\Tumble;

use Carbon\Carbon;
use App\Models\BelongsToBuyer;
use App\Models\BelongsToFactory;
use App\Models\CommonModelTrait;
use Illuminate\Http\Request;
use SkylarkSoft\GoRMG\Dyeing\Filters\Filter;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use SkylarkSoft\GoRMG\SystemSettings\Models\Shift;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use SkylarkSoft\GoRMG\Dyeing\Services\TextileServices\UId\TumbleService;
use SkylarkSoft\GoRMG\Dyeing\Models\TextileModels\DyeingBatch\DyeingBatch;
use SkylarkSoft\GoRMG\Dyeing\Models\TextileModels\TextileOrders\TextileOrder;

class Tumble extends Model
{
    use SoftDeletes;
    use CommonModelTrait;
    use BelongsToFactory;
    use BelongsToBuyer;

    protected $table = 'tumbles';
    protected $primaryKey = 'id';
    protected $fillable = [
        'unique_id',
        'factory_id',
        'buyer_id',
        'entry_basis',
        'dyeing_batch_id',
        'dyeing_batch_no',
        'textile_order_id',
        'textile_order_no',
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

    public function getStreamingDateAttribute(): string
    {
        return Carbon::create($this->attributes['streaming_date'])->toDateTimeLocalString();
    }

    public function getDryDateAttribute(): string
    {
        return Carbon::create($this->attributes['dry_date'])->toDateTimeLocalString();
    }

    public static function booted()
    {
        static::saving(function ($model) {
            if (!$model->id && in_array('created_by', $model->getFillable())) {
                $model->unique_id = TumbleService::generateUniqueId();
            }
        });
    }

    /*------------------------------------------------ Start Relations -----------------------------------------------*/

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
        return $this->belongsTo(Shift::class, 'shift_id')
            ->withDefault();
    }

    public function tumbleDetails(): HasMany
    {
        return $this->hasMany(TumbleDetail::class, 'tumble_id', 'id');
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

    /*------------------------------------------------- End Relations ------------------------------------------------*/
}
