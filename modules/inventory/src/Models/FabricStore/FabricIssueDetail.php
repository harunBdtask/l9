<?php

namespace SkylarkSoft\GoRMG\Inventory\Models\FabricStore;

use App\Casts\Json;
use App\ModelCommonTrait;
use SkylarkSoft\GoRMG\Merchandising\Models\Order;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use SkylarkSoft\GoRMG\Inventory\Models\Store;
use SkylarkSoft\GoRMG\Inventory\Models\StoreRack;
use SkylarkSoft\GoRMG\Inventory\Models\StoreRoom;
use SkylarkSoft\GoRMG\Inventory\Models\StoreFloor;
use SkylarkSoft\GoRMG\Inventory\Models\StoreShelf;
use SkylarkSoft\GoRMG\SystemSettings\Models\Buyer;
use SkylarkSoft\GoRMG\SystemSettings\Models\Color;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use SkylarkSoft\GoRMG\SystemSettings\Models\GarmentsItem;
use SkylarkSoft\GoRMG\SystemSettings\Models\UnitOfMeasurement;
use SkylarkSoft\GoRMG\Subcontract\Services\FabricCompositionService;
use SkylarkSoft\GoRMG\Merchandising\Models\Bookings\FabricBookingDetails;

class FabricIssueDetail extends Model
{
    use SoftDeletes, ModelCommonTrait;

    protected $table = 'fabric_issue_details';

    protected $primaryKey = 'id';

    protected $fillable = [
        'unique_id',
        'fabric_receive_id',
        'fabric_receive_details_id',
        'fabric_barcode_detail_id',
        'issue_type',
        'sample_type',
        'issue_id',
        'issue_date',
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
        'issue_qty',
        'issue_qty_details',
        'rate',
        'amount',
        'fabric_shade',
        'no_of_roll',
        'store_id',
        'floor_id',
        'room_id',
        'rack_id',
        'shelf_id',
        'cutting_unit_no',
        'remarks',
        'color_type_id',
        'created_by',
        'updated_by',
        'deleted_by',
    ];

    const UOM = [
        1 => 'Kg',
        2 => 'Yards',
        3 => 'Meter',
        4 => 'Pcs',
        5 => 'Cone(5k)',
    ];

    protected $casts = [
        'issue_qty_details' => Json::class,
    ];

    protected $appends = [
        'fabric_composition_value',
    ];

    public function getFabricCompositionValueAttribute(): ?string
    {
        return isset($this->attributes['fabric_composition_id'])
            // ? FabricDescriptionService::description($this->attributes['fabric_composition_id'])
            ? FabricCompositionService::description($this->attributes['fabric_composition_id'])
            : null;
    }

    public function issue(): BelongsTo
    {
        return $this->belongsTo(FabricIssue::class, 'issue_id')->withDefault();
    }

    public function receive(): BelongsTo
    {
        return $this->belongsTo(FabricReceive::class, 'fabric_receive_id')->withDefault();
    }

    public function receiveDetail(): BelongsTo
    {
        return $this->belongsTo(FabricReceiveDetail::class, 'fabric_receive_details_id')->withDefault();
    }

    public function barcodeDetail(): BelongsTo
    {
        return $this->belongsTo(FabricBarcodeDetail::class, 'fabric_barcode_detail_id')->withDefault();
    }

    public function bookingDetail(): BelongsTo
    {
        return $this->belongsTo(FabricBookingDetails::class, 'unique_id', 'unique_id')->withDefault();
    }

    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class, 'unique_id', 'job_no')->withDefault();
    }

    public function issueReturnDetails(): HasMany
    {
        return $this->hasMany(FabricIssueReturnDetail::class, 'fabric_issue_detail_id');
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

    public function buyer(): BelongsTo
    {
        return $this->belongsTo(Buyer::class, 'buyer_id')->withDefault();
    }
}
