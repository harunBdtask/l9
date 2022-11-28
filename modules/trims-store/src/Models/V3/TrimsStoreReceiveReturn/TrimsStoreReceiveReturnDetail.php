<?php

namespace SkylarkSoft\GoRMG\TrimsStore\Models\V3\TrimsStoreReceiveReturn;

use App\Models\BelongsToBin;
use App\Models\BelongsToBuyer;
use App\Models\BelongsToFloor;
use App\Models\BelongsToRack;
use App\Models\BelongsToRoom;
use App\Models\BelongsToShelf;
use App\Models\BelongsToStore;
use App\Models\BelongsToSupplier;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use SkylarkSoft\GoRMG\Merchandising\Models\Order;
use SkylarkSoft\GoRMG\SystemSettings\Models\Color;
use SkylarkSoft\GoRMG\SystemSettings\Models\Currency;
use SkylarkSoft\GoRMG\SystemSettings\Models\ItemGroup;
use SkylarkSoft\GoRMG\SystemSettings\Models\Size;
use SkylarkSoft\GoRMG\SystemSettings\Models\UnitOfMeasurement;
use SkylarkSoft\GoRMG\TrimsStore\Services\V3\UID\TrimsStoreReceiveReturn\TrimsStoreReceiveReturnDetailService;
use SkylarkSoft\GoRMG\TrimsStore\Traits\CommonBooted;

class TrimsStoreReceiveReturnDetail extends Model
{
    use SoftDeletes;
    use CommonBooted;
    use BelongsToBuyer;
    use BelongsToSupplier;
    use BelongsToStore;
    use BelongsToFloor;
    use BelongsToRoom;
    use BelongsToRack;
    use BelongsToShelf;
    use BelongsToBin;

    protected $table = 'v3_trims_store_receive_return_details';
    protected $primaryKey = 'id';
    protected $fillable = [
        'unique_id',
        'trims_store_receive_return_id',
        'return_to',
        'factory_id',
        'buyer_id',
        'style_id',
        'transaction_date',
        'po_numbers',
        'pi_numbers',
        'booking_id',
        'booking_no',
        'supplier_id',
        'garments_item_id',
        'item_code',
        'item_id',
        'sensitivity_id',
        'brand_name',
        'item_description',
        'color_id',
        'size_id',
        'order_qty',
        'wo_qty',
        'receive_return_qty',
        'uom_id',
        'currency_id',
        'rate',
        'exchange_rate',
        'amount',
        'floor_id',
        'room_id',
        'rack_id',
        'shelf_id',
        'bin_id',
        'remarks',
        'created_by',
        'updated_by',
        'deleted_by',
    ];

    public static function booted()
    {
        static::saving(function ($model) {
            if (! $model->id && in_array('created_by', $model->getFillable())) {
                $model->unique_id = TrimsStoreReceiveReturnDetailService::generateUniqueId();
            }
        });
    }

    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class, 'style_id')->withDefault();
    }

    public function itemGroup(): BelongsTo
    {
        return $this->belongsTo(ItemGroup::class, 'item_id')->withDefault();
    }

    public function color(): BelongsTo
    {
        return $this->belongsTo(Color::class, 'color_id')->withDefault();
    }

    public function size(): BelongsTo
    {
        return $this->belongsTo(Size::class, 'size_id')->withDefault();
    }

    public function uom(): BelongsTo
    {
        return $this->belongsTo(UnitOfMeasurement::class, 'uom_id')->withDefault();
    }

    public function currency(): BelongsTo
    {
        return $this->belongsTo(Currency::class, 'currency_id')->withDefault();
    }
}
