<?php

namespace SkylarkSoft\GoRMG\Inventory\Models\FabricStore;

use App\ModelCommonTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use SkylarkSoft\GoRMG\Inventory\Models\Store;
use SkylarkSoft\GoRMG\Inventory\Models\StoreRack;
use SkylarkSoft\GoRMG\Inventory\Models\StoreRoom;
use SkylarkSoft\GoRMG\Inventory\Models\StoreFloor;
use SkylarkSoft\GoRMG\Inventory\Models\StoreShelf;
use SkylarkSoft\GoRMG\SystemSettings\Models\Color;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use SkylarkSoft\GoRMG\SystemSettings\Models\GarmentsItem;
use SkylarkSoft\GoRMG\SystemSettings\Models\UnitOfMeasurement;
use SkylarkSoft\GoRMG\Merchandising\Models\Bookings\FabricBookingDetails;

class FabricIssueReturnDetail extends Model
{
    use ModelCommonTrait, SoftDeletes;

    const MANUAL = 'manual', BARCODE = 'barcode';

    protected $table = 'fabric_issue_return_details';

    protected $primaryKey = 'id';

    protected $fillable = [
        'unique_id',
        'issue_return_id',
        'fabric_issue_detail_id',
        'issue_return_type',
        'buyer_id',
        'style_id',
        'style_name',
        'po_no',
        'batch_no',
        'gmts_item_id',
        'body_part_id',
        'fabric_composition_id',
        'construction',
        'fabric_description',
        'dia',
        'ac_dia',
        'gsm',
        'ac_gsm',
        'dia_type',
        'ac_dia_type',
        'color_id',
        'contrast_color_id',
        'uom_id',
        'return_qty',
        'rate',
        'amount',
        'fabric_shade',
        'no_of_roll',
        'store_id',
        'floor_id',
        'room_id',
        'rack_id',
        'shelf_id',
        'remarks',
        'color_type_id',
        'created_by',
        'updated_by',
        'deleted_by',
    ];

    public function issueReturn(): BelongsTo
    {
        return $this->belongsTo(FabricIssueReturn::class, 'issue_return_id')->withDefault();
    }

    public function bookingDetail(): BelongsTo
    {
        return $this->belongsTo(FabricBookingDetails::class, 'unique_id', 'unique_id')->withDefault();
    }

    public function receiveDetail(): BelongsTo
    {
        return $this->belongsTo(FabricReceiveDetail::class, 'unique_id', 'unique_id')->withDefault();
    }

    public function issueDetail(): BelongsTo
    {
        return $this->belongsTo(FabricIssueDetail::class, 'fabric_issue_detail_id')->withDefault();
    }

    public function store(): BelongsTo
    {
        return $this->belongsTo(Store::class, 'store_id')->withDefault();
    }

    public function color(): BelongsTo
    {
        return $this->belongsTo(Color::class, 'color_id')->withDefault();
    }

    public function gmtsItem(): BelongsTo
    {
        return $this->belongsTo(GarmentsItem::class, 'gmts_item_id')->withDefault();
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
}
