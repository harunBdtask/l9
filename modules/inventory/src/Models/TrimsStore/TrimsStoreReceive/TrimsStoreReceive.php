<?php

namespace SkylarkSoft\GoRMG\Inventory\Models\TrimsStore\TrimsStoreReceive;

use App\Models\BelongsToFactory;
use App\Models\CommonModelTrait;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Http\Request;
use SkylarkSoft\GoRMG\Inventory\Filters\Filter;
use SkylarkSoft\GoRMG\Inventory\Models\Store;
use SkylarkSoft\GoRMG\Inventory\Models\TrimsStore\TrimsInventory\TrimsInventory;
use SkylarkSoft\GoRMG\Inventory\Services\UID\TrimsStoreReceiveService;
use SkylarkSoft\GoRMG\Merchandising\Models\Bookings\TrimsBooking;
use SkylarkSoft\GoRMG\SystemSettings\Models\Buyer;
use SkylarkSoft\GoRMG\SystemSettings\Models\Factory;

class TrimsStoreReceive extends Model
{
    use SoftDeletes;
    use CommonModelTrait;
    use BelongsToFactory;

    protected $table = 'trims_store_receives';
    protected $primaryKey = 'id';
    protected $fillable = [
        'unique_id',
        'style_name',
        'factory_id',
        'buyer_id',
        'store_id',
        'booking_id',
        'booking_no',
        'trims_inventory_id',
        'booking_date',
        'delivery_qty',
        'pi_no',
        'pi_receive_date',
        'challan_no',
        'remarks',
        'receive_date',
        'created_by',
        'updated_by',
        'deleted_by',
    ];

    public static function booted()
    {
        static::saving(function ($model) {
            if (!$model->id && in_array('created_by', $model->getFillable())) {
                $model->unique_id = TrimsStoreReceiveService::generateUniqueId();
            }
        });
    }

    public function scopeSearch(Builder $query, Request $request)
    {
        return $query->when($request, function (Builder $query) use ($request) {

            $inventoryNo = $request->get('inventory_no');
            $factoryId = $request->get('factory_id');
            $buyerId = $request->get('buyer_id');
            $styleName = $request->get('style_name');
            $bookingNo = $request->get('booking_no');
            $challanNo = $request->get('challan_no');
            $poNo = $request->get('po_no');
            $fromDate = $request->query('from_date');
            $toDate = $request->query('to_date');

            return $query->when($inventoryNo, function (Builder $query) use ($inventoryNo) {
                return $query->whereHas('trimsInventory', function (Builder $query) use ($inventoryNo) {
                    return $query->where('bin_no', $inventoryNo);
                });
            })->when($factoryId, function (Builder $query) use ($factoryId) {
                return $query->where('factory_id', $factoryId);
            })->when($buyerId, function (Builder $query) use ($buyerId) {
                return $query->where('buyer_id', $buyerId);
            })->when($styleName, function (Builder $query) use ($styleName) {
                return $query->where('style_name', $styleName);
            })->when($bookingNo, function (Builder $query) use ($bookingNo) {
                return $query->where('booking_no', $bookingNo);
            })->when($challanNo, function (Builder $query) use ($challanNo) {
                return $query->where('challan_no', $challanNo);
            })->when($poNo, function (Builder $query) use ($poNo) {
                return $query->whereHas('booking', function (Builder $query) use ($poNo) {
                    return $query->whereHas('bookingDetails', function (Builder $query) use ($poNo) {
                        return $query->where('po_no', 'LIKE', "%{$poNo}%");
                    });
                });
            })->when($fromDate && $toDate, Filter::betweenFilter('created_at', [$fromDate, $toDate]));

        });
    }

    public function booking(): BelongsTo
    {
        return $this->belongsTo(TrimsBooking::class, 'booking_id')->withDefault();
    }

    public function details(): HasMany
    {
        return $this->hasMany(TrimsStoreReceiveDetail::class, 'trims_store_receive_id', 'id');
    }

    public function trimsInventory(): BelongsTo
    {
        return $this->belongsTo(TrimsInventory::class, 'trims_inventory_id')->withDefault();
    }

    public function buyer(): BelongsTo
    {
        return $this->belongsTo(Buyer::class, 'buyer_id')->withDefault();
    }

    public function factory(): BelongsTo
    {
        return $this->belongsTo(Factory::class, 'factory_id')->withDefault();
    }

    public function store(): BelongsTo
    {
        return $this->belongsTo(Store::class)->withDefault();
    }
}
