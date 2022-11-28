<?php

namespace SkylarkSoft\GoRMG\Inputdroplets\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use SkylarkSoft\GoRMG\Merchandising\Models\Order;
use SkylarkSoft\GoRMG\Merchandising\Models\PurchaseOrder;
use SkylarkSoft\GoRMG\SystemSettings\Models\Buyer;
use SkylarkSoft\GoRMG\SystemSettings\Models\Color;
use SkylarkSoft\GoRMG\SystemSettings\Models\Floor;
use SkylarkSoft\GoRMG\SystemSettings\Models\GarmentsItem;
use SkylarkSoft\GoRMG\SystemSettings\Models\Line;
use SkylarkSoft\GoRMG\SystemSettings\Models\Size;

class DailyChallanSizeWiseInput extends Model
{
    protected $table = "daily_challan_size_wise_inputs";
    protected $primaryKey = "id";
    protected $fillable = [
        'floor_id',
        'line_id',
        'buyer_id',
        'order_id',
        'purchase_order_id',
        'garments_item_id',
        'color_id',
        'size_id',
        'challan_no',
        'production_date',
        'sewing_input',
        'factory_id'
    ];

    public function floors(): BelongsTo
    {
        return $this->belongsTo(Floor::class, 'floor_id', 'id');
    }

    public function floorsWithoutGlobalScopes(): BelongsTo
    {
        return $this->belongsTo(Floor::class, 'floor_id', 'id')->withoutGlobalScopes();
    }

    public function lines(): BelongsTo
    {
        return $this->belongsTo(Line::class, 'line_id', 'id');
    }

    public function linesWithoutGlobalScopes(): BelongsTo
    {
        return $this->belongsTo(Line::class, 'line_id', 'id')->withoutGlobalScopes();
    }

    public function buyer(): BelongsTo
    {
        return $this->belongsTo(Buyer::class, 'buyer_id')->withDefault();
    }

    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class, 'order_id')->withDefault();
    }

    public function purchaseOrder(): BelongsTo
    {
        return $this->belongsTo(PurchaseOrder::class, 'purchase_order_id')->withDefault();
    }

    public function garmentsItem(): BelongsTo
    {
        return $this->belongsTo(GarmentsItem::class, 'garments_item_id')->withDefault();
    }

    public function cuttingInventoryChallan(): BelongsTo
    {
        return $this->belongsTo(CuttingInventoryChallan::class, 'challan_no', 'challan_no')->withDefault();
    }

    public function color(): BelongsTo
    {
        return $this->belongsTo(Color::class, 'color_id')->withDefault();
    }

    public function size(): BelongsTo
    {
        return $this->belongsTo(Size::class, 'size_id')->withDefault();
    }
}
