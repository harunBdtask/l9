<?php

namespace SkylarkSoft\GoRMG\Cuttingdroplets\Models;

use App\FactoryIdTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use SkylarkSoft\GoRMG\Merchandising\Models\Order;
use SkylarkSoft\GoRMG\Merchandising\Models\PurchaseOrder;
use SkylarkSoft\GoRMG\SystemSettings\Models\Buyer;
use SkylarkSoft\GoRMG\SystemSettings\Models\Color;
use SkylarkSoft\GoRMG\SystemSettings\Models\Factory;
use SkylarkSoft\GoRMG\SystemSettings\Models\Size;

class ColorSizeSummaryReport extends Model
{
    use FactoryIdTrait;

    protected $table = 'color_size_summary_reports';

    protected $fillable = [
        'buyer_id',
        'order_id',
        'purchase_order_id',
        'color_id',
        'size_id',
        'total_cutting',
        'total_cutting_rejection',
        'total_input',
        'total_sewing_output',
        'total_sewing_rejection',
        'factory_id'
    ];

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

    public function color(): BelongsTo
    {
        return $this->belongsTo(Color::class, 'color_id', 'id')->withDefault();
    }

    public function size(): BelongsTo
    {
        return $this->belongsTo(Size::class, 'size_id', 'id')->withDefault();
    }

    public function factory(): BelongsTo
    {
        return $this->belongsTo(Factory::class, 'factory_id', 'id')->withDefault();
    }
}
