<?php

namespace SkylarkSoft\GoRMG\Inventory\Models\TrimsStore\TrimsStoreMrr;

use App\Models\BelongsToFactory;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Http\Request;
use SkylarkSoft\GoRMG\Inventory\Filters\Filter;
use SkylarkSoft\GoRMG\Inventory\Models\Store;
use SkylarkSoft\GoRMG\Inventory\Services\UID\TrimsStoreMrrService;
use SkylarkSoft\GoRMG\Knitting\Traits\CommonBooted;
use SkylarkSoft\GoRMG\Merchandising\Models\Bookings\TrimsBooking;
use SkylarkSoft\GoRMG\SystemSettings\Models\Buyer;
use SkylarkSoft\GoRMG\SystemSettings\Models\Factory;

class TrimsStoreMrr extends Model
{
    use SoftDeletes;
    use CommonBooted;
    use BelongsToFactory;

    protected $table = 'trims_store_mrr';
    protected $primaryKey = 'id';
    protected $fillable = [
        'mrr_no',
        'style_name',
        'trims_store_receive_id',
        'store_id',
        'buyer_id',
        'factory_id',
        'booking_id',
        'booking_no',
        'booking_date',
        'booking_qty',
        'booking_amount',
        'delivery_amount',
        'delivery_date',
        'challan_no',
        'qc_date',
        'mrr_date',
        'pi_no',
        'pi_receive_date',
        'others',
        'created_by',
        'updated_by',
        'deleted_by',
    ];

    public static function booted()
    {
        static::saving(function ($model) {
            if (!$model->id && in_array('created_by', $model->getFillable())) {
                $model->mrr_no = TrimsStoreMrrService::generateUniqueId();
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
                    return $query->where('mrr_no', $mrrNo);
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

    public function details(): HasMany
    {
        return $this->hasMany(TrimsStoreMrrDetail::class, 'trims_store_mrr_id');
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
