<?php

namespace SkylarkSoft\GoRMG\Inventory\Models\TrimsStore\TrimsStoreMrr;

use App\Models\BelongsToFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use SkylarkSoft\GoRMG\Inventory\Models\TrimsStore\TrimsStoreBinCard\TrimsStoreBinCardDetail;
use SkylarkSoft\GoRMG\Inventory\Models\TrimsStore\TrimsStoreReceive\TrimsStoreReceiveDetail;
use SkylarkSoft\GoRMG\Knitting\Traits\CommonBooted;
use SkylarkSoft\GoRMG\Merchandising\Models\Bookings\TrimsBooking;
use SkylarkSoft\GoRMG\Merchandising\Models\Bookings\TrimsBookingDetails;
use SkylarkSoft\GoRMG\SystemSettings\Models\Color;
use SkylarkSoft\GoRMG\SystemSettings\Models\ItemGroup;
use SkylarkSoft\GoRMG\SystemSettings\Models\UnitOfMeasurement;

class TrimsStoreMrrDetail extends Model
{
    use SoftDeletes;
    use CommonBooted;
    use BelongsToFactory;

    const OPTIONS = [
        1 => 'OK',
        2 => 'NOT OK',
    ];

    protected $table = 'trims_store_mrr_details';
    protected $primaryKey = 'id';
    protected $fillable = [
        'trims_store_mrr_id',
        'trims_store_receive_id',
        'trims_store_receive_detail_id',
        'factory_id',
        'store_id',
        'item_id',
        'uom_id',
        'color_id',
        'item_description',
        'size_id',
        'size',
        'planned_garments_qty',
        'approval_shade_code',
        'actual_consumption',
        'total_consumption',
        'actual_qty',
        'total_delivered_qty',
        'rate',
        'amount',
        'remarks',
        'created_by',
        'updated_by',
        'deleted_by',
    ];

    protected $appends = [];

    public function trimsBooking(): BelongsTo
    {
        return $this->belongsTo(TrimsBooking::class, 'trims_booking_id', 'id')
            ->withDefault();
    }

    public function trimsBookingDetail(): BelongsTo
    {
        return $this->belongsTo(TrimsBookingDetails::class, 'trims_booking_detail_id', 'id')
            ->withDefault();
    }

    public function trimsStoreReceiveDetail(): BelongsTo
    {
        return $this->belongsTo(TrimsStoreReceiveDetail::class, 'trims_store_receive_detail_id', 'id')
            ->withDefault();
    }

    public function trimsMrr(): BelongsTo
    {
        return $this->belongsTo(TrimsStoreMrr::class, 'trims_store_mrr_id', 'id')
            ->withDefault();
    }

    public function binCardDetails(): HasMany
    {
        return $this->hasMany(TrimsStoreBinCardDetail::class, 'trims_store_mrr_detail_id');
    }

    public function color(): BelongsTo
    {
        return $this->belongsTo(Color::class, 'color_id')->withDefault();
    }

    public function size()
    {
        //
    }

    public function itemGroup(): BelongsTo
    {
        return $this->belongsTo(ItemGroup::class, 'item_id')->withDefault();
    }

    public function uom(): BelongsTo
    {
        return $this->belongsTo(UnitOfMeasurement::class, 'uom_id')->withDefault();
    }
}
