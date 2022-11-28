<?php

namespace SkylarkSoft\GoRMG\Inventory\Models\TrimsStore\TrimsInventory;

use App\Models\BelongsToFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use SkylarkSoft\GoRMG\Inventory\Models\TrimsStore\TrimsStoreReceive\TrimsStoreReceiveDetail;
use SkylarkSoft\GoRMG\Knitting\Traits\CommonBooted;
use SkylarkSoft\GoRMG\Merchandising\Models\Bookings\TrimsBooking;
use SkylarkSoft\GoRMG\Merchandising\Models\Bookings\TrimsBookingDetails;
use SkylarkSoft\GoRMG\SystemSettings\Models\Color;
use SkylarkSoft\GoRMG\SystemSettings\Models\ItemGroup;
use SkylarkSoft\GoRMG\SystemSettings\Models\UnitOfMeasurement;

class TrimsInventoryDetail extends Model
{
    use SoftDeletes;
    use CommonBooted;
    use BelongsToFactory;

    const OPTIONS = [
        1 => 'OK',
        2 => 'NOT OK',
    ];

    protected $table = 'trims_inventory_details';
    protected $primaryKey = 'id';
    protected $fillable = [
        'trims_booking_id',
        'trims_booking_detail_id',
        'trims_inventory_id',
        'factory_id',
        'store_id',
        'receive_date',
        'sensitivity',
        'item_id',
        'item_description',
        'color_id',
        'size_id',
        'size',
        'uom_id',
        'approval_shade_code',
        'delivery_swatch',
        'is_color',
        'planned_garments_qty',
        'booking_qty',
        'receive_qty',
        'excess_qty',
        'reject_qty',
        'rate',
        'amount',
        'is_qty',
        'quality',
        'dimensions',
        'cf_to_wah',
        'inventory_by',
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

    public function trimsInventory(): BelongsTo
    {
        return $this->belongsTo(TrimsInventory::class, 'trims_inventory_id', 'id')
            ->withDefault();
    }

    public function receiveDetails(): HasMany
    {
        return $this->hasMany(TrimsStoreReceiveDetail::class, 'trims_inventory_detail_id');
    }

    public function color(): BelongsTo
    {
        return $this->belongsTo(Color::class, 'color_id')->withDefault();
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
