<?php

namespace SkylarkSoft\GoRMG\Inventory\Models\TrimsStore\TrimsStoreIssue;

use App\Models\BelongsToFactory;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Http\Request;
use SkylarkSoft\GoRMG\Inventory\Filters\Filter;
use SkylarkSoft\GoRMG\Inventory\Models\Store;
use SkylarkSoft\GoRMG\Inventory\Services\UID\TrimsStoreIssueService;
use SkylarkSoft\GoRMG\Knitting\Traits\CommonBooted;
use SkylarkSoft\GoRMG\Merchandising\Models\Bookings\TrimsBooking;
use SkylarkSoft\GoRMG\SystemSettings\Models\Buyer;
use SkylarkSoft\GoRMG\SystemSettings\Models\Factory;

class TrimsStoreIssue extends Model
{
    use SoftDeletes;
    use CommonBooted;
    use BelongsToFactory;

    protected $table = 'trims_store_issues';
    protected $primaryKey = 'id';
    protected $fillable = [
        'unique_id',
        'style_name',
        'factory_id',
        'buyer_id',
        'store_id',
        'booking_id',
        'trims_store_bin_card_id',
        'booking_no',
        'mrr_date',
        'booking_date',
        'delivery_date',
        'booking_qty',
        'delivery_qty',
        'excess_delivery_qty',
        'pi_no',
        'challan_no',
        'others',
        'bin_number',
        'created_by',
        'updated_by',
        'deleted_by',
    ];

    public static function booted()
    {
        static::saving(function ($model) {
            if (!$model->id && in_array('created_by', $model->getFillable())) {
                $model->unique_id = TrimsStoreIssueService::generateUniqueId();
            }
        });
    }

    public function scopeSearch(Builder $query, Request $request)
    {
        $query->when($request, function (Builder $query) use ($request) {

            $uniqueId = $request->get('unique_id');
            $factoryId = $request->get('factory_id');
            $buyerId = $request->get('buyer_id');
            $bookingNo = $request->get('booking_no');
            $challanNo = $request->get('challan_no');
            $bookingDate = $request->get('booking_date');
            $fromDate = $request->query('from_date');
            $toDate = $request->query('to_date');

            return $query->when($uniqueId, function (Builder $query) use ($uniqueId) {
                    return $query->where('unique_id', $uniqueId);
            })->when($factoryId, function (Builder $query) use ($factoryId) {
                return $query->where('factory_id', $factoryId);
            })->when($buyerId, function (Builder $query) use ($buyerId) {
                return $query->where('buyer_id', $buyerId);
            })->when($bookingNo, function (Builder $query) use ($bookingNo) {
                return $query->where('booking_no', $bookingNo);
            })->when($challanNo, function (Builder $query) use ($challanNo) {
                return $query->where('challan_no', $challanNo);
            })->when($bookingDate, function (Builder $query) use ($bookingDate) {
                return $query->where('booking_date', $bookingDate);
            })->when($fromDate && $toDate, Filter::betweenFilter('created_at', [$fromDate, $toDate]));

        });
    }

    public function details(): HasMany
    {
        return $this->hasMany(TrimsStoreIssueDetail::class, 'trims_store_issue_id');
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
