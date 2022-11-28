<?php

namespace SkylarkSoft\GoRMG\Inventory\Models\TrimsStore\TrimsStoreBinCard;

use App\Models\BelongsToFactory;
use App\Models\CommonModelTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use SkylarkSoft\GoRMG\Inventory\Models\StoreBin;
use SkylarkSoft\GoRMG\Inventory\Models\StoreFloor;
use SkylarkSoft\GoRMG\Inventory\Models\StoreRack;
use SkylarkSoft\GoRMG\Inventory\Models\StoreRoom;
use SkylarkSoft\GoRMG\Inventory\Models\StoreShelf;
use SkylarkSoft\GoRMG\Inventory\Models\TrimsStore\TrimsStoreIssue\TrimsStoreIssueDetail;
use SkylarkSoft\GoRMG\Inventory\Models\TrimsStore\TrimsStoreMrr\TrimsStoreMrrDetail;
use SkylarkSoft\GoRMG\Merchandising\Models\Bookings\TrimsBooking;
use SkylarkSoft\GoRMG\Merchandising\Models\Bookings\TrimsBookingDetails;
use SkylarkSoft\GoRMG\SystemSettings\Models\Color;
use SkylarkSoft\GoRMG\SystemSettings\Models\ItemGroup;
use SkylarkSoft\GoRMG\SystemSettings\Models\UnitOfMeasurement;

class TrimsStoreBinCardDetail extends Model
{
    use SoftDeletes;
    use CommonModelTrait;
    use BelongsToFactory;

    protected $table = 'trims_store_bin_card_details';
    protected $primaryKey = 'id';
    protected $fillable = [
        'trims_store_mrr_id',
        'trims_store_mrr_detail_id',
        'trims_store_bin_card_id',
        'factory_id',
        'store_id',
        'bin_card_date',
        'item_id',
        'item_description',
        'color_id',
        'size_id',
        'size',
        'uom_id',
        'approval_shade_code',
        'booking_qty',
        'floor_id',
        'room_id',
        'rack_id',
        'shelf_id',
        'bin_id',
        'issue_qty',
        'issue_date',
        'issue_to',
        'remarks',
        'planned_garments_qty',
        'created_by',
        'updated_by',
        'deleted_by',
    ];

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

    public function mrrDetail(): BelongsTo
    {
        return $this->belongsTo(TrimsStoreMrrDetail::class, 'trims_store_mrr_detail_id')->withDefault();
    }

    public function issueDetails(): HasMany
    {
        return $this->hasMany(TrimsStoreIssueDetail::class, 'trims_store_bin_card_detail_id');
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

    public function floor(): BelongsTo
    {
        return $this->belongsTo(StoreFloor::class, 'floor_id')->withDefault();
    }

    public function room(): BelongsTo
    {
        return $this->belongsTo(StoreRoom::class, 'room_id')->withDefault();
    }

    public function rack(): BelongsTo
    {
        return $this->belongsTo(StoreRack::class, 'rack_id')->withDefault();
    }

    public function shelf(): BelongsTo
    {
        return $this->belongsTo(StoreShelf::class, 'shelf_id')->withDefault();
    }

    public function bin(): BelongsTo
    {
        return $this->belongsTo(StoreBin::class, 'bin_id')->withDefault();
    }
}
