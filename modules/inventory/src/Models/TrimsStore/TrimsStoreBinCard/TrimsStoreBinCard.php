<?php

namespace SkylarkSoft\GoRMG\Inventory\Models\TrimsStore\TrimsStoreBinCard;

use App\Models\BelongsToBuyer;
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
use SkylarkSoft\GoRMG\Inventory\Models\TrimsStore\TrimsStoreMrr\TrimsStoreMrr;
use SkylarkSoft\GoRMG\Inventory\Services\UID\TrimsStoreBinCardService;
use SkylarkSoft\GoRMG\Merchandising\Models\Bookings\TrimsBooking;
use SkylarkSoft\GoRMG\SystemSettings\Models\Buyer;

class TrimsStoreBinCard extends Model
{
    use SoftDeletes;
    use CommonModelTrait;
    use BelongsToFactory;
    use BelongsToBuyer;

    protected $table = 'trims_store_bin_cards';
    protected $primaryKey = 'id';
    protected $fillable = [
        'unique_id',
        'style_name',
        'factory_id',
        'buyer_id',
        'store_id',
        'booking_id',
        'booking_no',
        'trims_store_mrr_id',
        'booking_date',
        'mrr_date',
        'delivery_date',
        'booking_qty',
        'delivery_qty',
        'excess_delivery_qty',
        'pi_no',
        'challan_no',
        'remarks',
        'bin_no',
        'created_by',
        'updated_by',
        'deleted_by',
    ];

    public static function booted()
    {
        static::saving(function ($model) {
            if (!$model->id && in_array('created_by', $model->getFillable())) {
                $model->unique_id = TrimsStoreBinCardService::generateUniqueId();
            }
        });
    }

    public function scopeSearch(Builder $query, Request $request)
    {
        $query->when($request, function (Builder $query) use ($request) {

            $mrrNo = $request->get('mrr_no');
            $factoryId = $request->get('factory_id');
            $buyerId = $request->get('buyer_id');
            $bookingNo = $request->get('booking_no');
            $challanNo = $request->get('challan_no');
            $deliveryDate = $request->get('delivery_date');
            $bookingDate = $request->get('booking_date');
            $fromDate = $request->query('from_date');
            $toDate = $request->query('to_date');

            return $query->when($mrrNo, function (Builder $query) use ($mrrNo) {
                return $query->whereHas('trimsStoreMRR', function (Builder $query) use ($mrrNo) {
                    return $query->where('mrr_no', $mrrNo);
                });
            })->when($factoryId, function (Builder $query) use ($factoryId) {
                return $query->where('factory_id', $factoryId);
            })->when($buyerId, function (Builder $query) use ($buyerId) {
                return $query->where('buyer_id', $buyerId);
            })->when($bookingNo, function (Builder $query) use ($bookingNo) {
                return $query->where('booking_no', $bookingNo);
            })->when($bookingDate, function (Builder $query) use ($bookingDate) {
                return $query->where('booking_date', $bookingDate);
            })->when($challanNo, function (Builder $query) use ($challanNo) {
                return $query->where('challan_no', $challanNo);
            })->when($deliveryDate, function (Builder $query) use ($deliveryDate) {
                return $query->where('delivery_date', $deliveryDate);
            })->when($fromDate && $toDate, Filter::betweenFilter('created_at', [$fromDate, $toDate]));

        });
    }

    public function booking(): BelongsTo
    {
        return $this->belongsTo(TrimsBooking::class, 'booking_id')->withDefault();
    }

    public function buyer(): BelongsTo
    {
        return $this->belongsTo(Buyer::class)->withDefault();
    }

    public function store(): BelongsTo
    {
        return $this->belongsTo(Store::class)->withDefault();
    }

    public function details(): HasMany
    {
        return $this->hasMany(TrimsStoreBinCardDetail::class, 'trims_store_bin_card_id');
    }

    public function trimsStoreMRR(): BelongsTo
    {
        return $this->belongsTo(TrimsStoreMrr::class, 'trims_store_mrr_id')->withDefault();
    }
}
