<?php

namespace SkylarkSoft\GoRMG\Inventory\Models\TrimsStore\TrimsStoreDeliveryChallan;

use App\Models\BelongsToFactory;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Http\Request;
use SkylarkSoft\GoRMG\Inventory\Filters\Filter;
use SkylarkSoft\GoRMG\Inventory\Models\Store;
use SkylarkSoft\GoRMG\Inventory\Services\UID\TrimsStoreDeliveryChallanService;
use SkylarkSoft\GoRMG\Knitting\Traits\CommonBooted;
use SkylarkSoft\GoRMG\Merchandising\Models\Bookings\TrimsBooking;
use SkylarkSoft\GoRMG\SystemSettings\Models\Buyer;
use SkylarkSoft\GoRMG\SystemSettings\Models\Factory;

class TrimsStoreDeliveryChallan extends Model
{
    use SoftDeletes;
    use CommonBooted;
    use BelongsToFactory;

    protected $table = 'trims_store_delivery_challans';
    protected $primaryKey = 'id';
    protected $fillable = [
        'unique_id',
        'style_name',
        'factory_id',
        'buyer_id',
        'store_id',
        'booking_id',
        'booking_no',
        'booking_date',
        'challan_no',
        'challan_date',
        'challan_qty',
        'challan_type',
        'booking_qty',
        'excess_delivery_qty',
        'pi_no',
        'created_by',
        'updated_by',
        'deleted_by',
    ];

    public static function booted()
    {
        static::saving(function ($model) {
            if (!$model->id && in_array('created_by', $model->getFillable())) {
                $model->unique_id = TrimsStoreDeliveryChallanService::generateUniqueId();
            }
        });
    }

    public function scopeSearch(Builder $query, Request $request)
    {
        $query->when($request, function (Builder $query) use ($request) {

            $factoryId = $request->get('factory_id');
            $buyerId = $request->get('buyer_id');
            $bookingNo = $request->get('booking_no');
            $challanNo = $request->get('challan_no');
            $bookingDate = $request->get('booking_date');
            $challanDate = $request->get('challan_date');
            $itemDescription = $request->get('item_description');
            $fromDate = $request->query('from_date');
            $toDate = $request->query('to_date');

            return $query->when($factoryId, function (Builder $query) use ($factoryId) {
                return $query->where('factory_id', $factoryId);
            })->when($buyerId, function (Builder $query) use ($buyerId) {
                return $query->where('buyer_id', $buyerId);
            })->when($bookingNo, function (Builder $query) use ($bookingNo) {
                return $query->where('booking_no', $bookingNo);
            })->when($challanNo, function (Builder $query) use ($challanNo) {
                return $query->where('challan_no', $challanNo);
            })->when($bookingDate, function (Builder $query) use ($bookingDate) {
                return $query->where('booking_date', $bookingDate);
            })->when($challanDate, function (Builder $query) use ($challanDate) {
                return $query->where('challan_date', $challanDate);
            })->when($itemDescription, function (Builder $query) use ($itemDescription) {
                return $query->whereHas('details', function (Builder $query) use ($itemDescription) {
                    return $query->where('item_description', $itemDescription);
                });
            })->when($fromDate && $toDate, Filter::betweenFilter('created_at', [$fromDate, $toDate]));
        });
    }

    public function details(): HasMany
    {
        return $this->hasMany(TrimsStoreDeliveryChallanDetail::class);
    }

    public function booking(): BelongsTo
    {
        return $this->belongsTo(TrimsBooking::class, 'booking_id')->withDefault();
    }

    public function factory(): BelongsTo
    {
        return $this->belongsTo(Factory::class)->withDefault();
    }

    public function buyer(): BelongsTo
    {
        return $this->belongsTo(Buyer::class)->withDefault();
    }

    public function store(): BelongsTo
    {
        return $this->belongsTo(Store::class)->withDefault();
    }
}
