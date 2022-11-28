<?php

namespace SkylarkSoft\GoRMG\Inventory\Models\FabricStore;

use App\Casts\Json;
use App\ModelCommonTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use SkylarkSoft\GoRMG\Inventory\Models\StoreRack;
use SkylarkSoft\GoRMG\Inventory\Models\StoreRoom;
use SkylarkSoft\GoRMG\Inventory\Models\StoreFloor;
use SkylarkSoft\GoRMG\Inventory\Models\StoreShelf;
use SkylarkSoft\GoRMG\Merchandising\Actions\StyleAuditReportAction;
use SkylarkSoft\GoRMG\Merchandising\Models\Order;
use SkylarkSoft\GoRMG\SystemSettings\Models\Buyer;
use SkylarkSoft\GoRMG\SystemSettings\Models\Color;
use SkylarkSoft\GoRMG\SystemSettings\Models\BodyPart;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use SkylarkSoft\GoRMG\SystemSettings\Models\GarmentsItem;
use SkylarkSoft\GoRMG\SystemSettings\Models\UnitOfMeasurement;
use SkylarkSoft\GoRMG\Merchandising\Models\Bookings\FabricBookingDetails;

class FabricReceiveDetail extends Model
{
    use SoftDeletes, ModelCommonTrait;

    protected $table = 'fabric_receive_details';

    protected $fillable = [
        'unique_id',
        'receive_id',
        'receivable_type',
        'receivable_id', // receivable_id in fabric_receives table
        'receive_date',
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
        'receive_qty',
        'rate',
        'amount',
        'reject_qty',
        'fabric_shade',
        'no_of_roll',
        'grey_used',
        'store_id',
        'floor_id',
        'room_id',
        'rack_id',
        'shelf_id',
        'remarks',
        'machine_name',
        'color_type_id',
        'created_by',
        'updated_by',
        'deleted_by',
    ];

    protected $casts = [
        'contrast_color_id' => Json::class,
    ];

    public function receive(): BelongsTo
    {
        return $this->belongsTo(FabricReceive::class, 'receive_id')->withDefault();
    }

    public function issueDetails(): HasMany
    {
        return $this->hasMany(FabricIssueDetail::class, 'fabric_receive_details_id');
    }

    public function receiveReturnDetails(): HasMany
    {
        return $this->hasMany(FabricReceiveReturnDetail::class, 'fabric_receive_detail_id');
    }

    public function barcodeDetails(): HasMany
    {
        return $this->hasMany(FabricBarcodeDetail::class, 'fabric_receive_detail_id');
    }

    public function bookingDetail(): BelongsTo
    {
        return $this->belongsTo(FabricBookingDetails::class, 'unique_id', 'unique_id')
            ->withDefault();
    }

    public function fabricColor(): BelongsTo
    {
        return $this->belongsTo(Color::class, 'color_id')->withDefault();
    }

    public function buyer(): BelongsTo
    {
        return $this->belongsTo(Buyer::class, 'buyer_id')->withDefault();
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

    public function body(): BelongsTo
    {
        return $this->belongsTo(BodyPart::class, 'body_part_id')->withDefault();
    }

    public function item(): BelongsTo
    {
        return $this->belongsTo(GarmentsItem::class, 'gmts_item_id')->withDefault();
    }

    public function color(): BelongsTo
    {
        return $this->belongsTo(Color::class, 'color_id')->withDefault();
    }

    public function returnDetails(): HasMany
    {
        return $this->hasMany(FabricReceiveReturnDetail::class, 'fabric_receive_detail_id');
    }

    public function orderStyle(): BelongsTo
    {
        return $this->belongsTo(Order::class, 'style_name','style_name')->withDefault();
    }

    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class, 'unique_id', 'job_no')->withDefault();
    }

    public static function booted()
    {
        static::created(function ($model) {
            (new StyleAuditReportAction())
                ->init($model->style_id)
                ->handleOrder()
                ->handleBudget()
                ->handleFinishFabric()
                ->saveOrUpdate();
        });

        static::updated(function ($model) {
            (new StyleAuditReportAction())
                ->init($model->style_id)
                ->handleOrder()
                ->handleBudget()
                ->handleFinishFabric()
                ->saveOrUpdate();
        });

        static::deleted(function ($model) {
            (new StyleAuditReportAction())
                ->init($model->style_id)
                ->handleOrder()
                ->handleBudget()
                ->handleFinishFabric()
                ->saveOrUpdate();
        });
    }
}
