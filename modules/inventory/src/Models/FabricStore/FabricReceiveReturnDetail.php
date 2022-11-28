<?php

namespace SkylarkSoft\GoRMG\Inventory\Models\FabricStore;

use App\Casts\Json;
use App\ModelCommonTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use SkylarkSoft\GoRMG\Inventory\Models\StoreRack;
use SkylarkSoft\GoRMG\Inventory\Models\StoreRoom;
use SkylarkSoft\GoRMG\Inventory\Models\StoreFloor;
use SkylarkSoft\GoRMG\SystemSettings\Models\Color;
use SkylarkSoft\GoRMG\Inventory\Models\StoreShelf;
use SkylarkSoft\GoRMG\SystemSettings\Models\Buyer;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use SkylarkSoft\GoRMG\SystemSettings\Models\BodyPart;
use SkylarkSoft\GoRMG\SystemSettings\Models\UnitOfMeasurement;

class FabricReceiveReturnDetail extends Model
{
    use SoftDeletes, ModelCommonTrait;

    protected $table = 'fabric_receive_return_details';
    protected $fillable = [
        'unique_id',
        'receive_return_id',
        'fabric_receive_detail_id',
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
        'deleted_by'
    ];

    protected $casts = [
        'contrast_color_id' => Json::class,
    ];

    public function receiveReturn(): BelongsTo
    {
        return $this->belongsTo(FabricReceiveReturn::class, 'receive_return_id')->withDefault();
    }

    public function receiveDetail(): BelongsTo
    {
        return $this->belongsTo(FabricReceiveDetail::class, 'fabric_receive_detail_id')
            ->withDefault();
    }

    public function color(): BelongsTo
    {
        return $this->belongsTo(Color::class, 'color_id')->withDefault();
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

    public function buyer(): BelongsTo
    {
        return $this->belongsTo(Buyer::class, 'buyer_id')->withDefault();
    }
}
