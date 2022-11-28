<?php

namespace SkylarkSoft\GoRMG\ManualProduction\Models\Reports;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use SkylarkSoft\GoRMG\ManualProduction\Models\Subcontract\SubcontractFactoryProfile;
use SkylarkSoft\GoRMG\Merchandising\Models\Order;
use SkylarkSoft\GoRMG\Merchandising\Models\PurchaseOrder;
use SkylarkSoft\GoRMG\SystemSettings\Models\Buyer;
use SkylarkSoft\GoRMG\SystemSettings\Models\Color;
use SkylarkSoft\GoRMG\SystemSettings\Models\Factory;
use SkylarkSoft\GoRMG\SystemSettings\Models\GarmentsItem;
use SkylarkSoft\GoRMG\SystemSettings\Models\Size;

class ManualTotalProductionReport extends Model
{
    use HasFactory;

    protected $table = "manual_total_production_reports";

    protected $fillable = [
        'factory_id',
        'subcontract_factory_id',
        'buyer_id',
        'order_id',
        'garments_item_id',
        'purchase_order_id',
        'color_id',
        'size_id',
        'cutting_qty',
        'cutting_rejection_qty',
        'print_sent_qty',
        'print_receive_qty',
        'print_rejection_qty',
        'embroidery_sent_qty',
        'embroidery_receive_qty',
        'embroidery_rejection_qty',
        'wash_sent_qty',
        'wash_receive_qty',
        'wash_rejection_qty',
        'special_works_sent_qty',
        'special_works_receive_qty',
        'special_works_rejection_qty',
        'others_sent_qty',
        'others_receive_qty',
        'others_rejection_qty',
        'input_qty',
        'sewing_output_qty',
        'sewing_rejection_qty',
        'iron_qty',
        'iron_rejection_qty',
        'poly_qty',
        'poly_rejection_qty'
    ];

    public function factory(): BelongsTo
    {
        return $this->belongsTo(Factory::class, 'factory_id', 'id')->withDefault();
    }

    public function subcontractFactory(): BelongsTo
    {
        return $this->belongsTo(SubcontractFactoryProfile::class, 'subcontract_factory_id', 'id')->withDefault();
    }

    public function buyer(): BelongsTo
    {
        return $this->belongsTo(Buyer::class, 'buyer_id', 'id')->withDefault();
    }

    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class, 'order_id', 'id')->withDefault();
    }

    public function garmentsItem(): BelongsTo
    {
        return $this->belongsTo(GarmentsItem::class, 'garments_item_id', 'id')->withDefault();
    }

    public function purchaseOrder(): BelongsTo
    {
        return $this->belongsTo(PurchaseOrder::class, 'purchase_order_id', 'id')->withDefault();
    }

    public function color(): BelongsTo
    {
        return $this->belongsTo(Color::class, 'color_id', 'id')->withDefault();
    }

    public function size(): BelongsTo
    {
        return $this->belongsTo(Size::class, 'size_id', 'id')->withDefault();
    }
}
