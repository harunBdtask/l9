<?php

namespace SkylarkSoft\GoRMG\Finishingdroplets\Models;

use App\Models\CommonModelTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use SkylarkSoft\GoRMG\Merchandising\Models\Order;
use SkylarkSoft\GoRMG\Merchandising\Models\PurchaseOrder;
use SkylarkSoft\GoRMG\SystemSettings\Models\Buyer;
use SkylarkSoft\GoRMG\SystemSettings\Models\Color;
use SkylarkSoft\GoRMG\SystemSettings\Models\Factory;
use SkylarkSoft\GoRMG\SystemSettings\Models\Size;
use SkylarkSoft\GoRMG\SystemSettings\Models\User;

class PnPackingList extends Model
{
    use HasFactory, SoftDeletes, CommonModelTrait;

    protected $table = 'pn_packing_list';
    protected $primaryKey = 'id';
    protected $fillable = [
        'uid',
        'production_date',
        'buyer_id',
        'order_id',
        'purchase_order_id',
        'color_id',
        'size_id',
        'size_wise_qty',
        'destination',
        'tag_type',
        'no_of_carton',
        'qty_per_carton',
        'no_of_boxes',
        'blister_kit_carton',
        'kit_bc_carton',
        'carton_no_from',
        'carton_no_to',
        'measurement_l',
        'measurement_w',
        'measurement_h',
        'bc_height',
        'gw_box_weight',
        'bc_gw',
        'nw_box_weight',
        'bc_nw',
        'm3_cbu',
        'type_of_shipment',
        'created_by',
        'updated_by',
        'deleted_by',
    ];

    protected $appends = [
        'type_of_shipment_value',
        'tag_type_value'
    ];

    const TYPE_OF_SHIPMENT = [
        1 => 'PARTIAL',
        2 => 'BALANCE',
        3 => 'SOLE',
    ];

    const TAG_TYPE = [
        1 => 'EURO TAG',
        2 => 'BLANK TAG'
    ];

    public function getTypeOfShipmentValueAttribute(): ?string
    {
        return self::TYPE_OF_SHIPMENT[$this->attributes['type_of_shipment']] ?? null;
    }

    public function getTagTypeValueAttribute(): ?string
    {
        return self::TAG_TYPE[$this->attributes['tag_type']] ?? null;
    }

    public function buyer(): BelongsTo
    {
        return $this->belongsTo(Buyer::class, 'buyer_id')->withDefault();
    }

    public function factory(): BelongsTo
    {
        return $this->belongsTo(Factory::class, 'factory_id')->withDefault();
    }

    public function purchaseOrder(): BelongsTo
    {
        return $this->belongsTo(PurchaseOrder::class, 'purchase_order_id')->withDefault();
    }

    public function color(): BelongsTo
    {
        return $this->belongsTo(Color::class, 'color_id')->withDefault();
    }

    public function size(): BelongsTo
    {
        return $this->belongsTo(Size::class, 'size_id')->withDefault();
    }

    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class, 'order_id')->withDefault();
    }

    public function CreatedByUser()
    {
        return $this->belongsTo(User::class, 'created_by')->withDefault();
    }


}
