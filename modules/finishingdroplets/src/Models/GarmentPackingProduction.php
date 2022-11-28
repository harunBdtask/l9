<?php

namespace SkylarkSoft\GoRMG\Finishingdroplets\Models;

use App\Casts\Json;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use SkylarkSoft\GoRMG\Knitting\Traits\CommonBooted;
use SkylarkSoft\GoRMG\Merchandising\Models\Order;
use SkylarkSoft\GoRMG\Merchandising\Models\PurchaseOrder;
use SkylarkSoft\GoRMG\SystemSettings\Models\Buyer;

class GarmentPackingProduction extends Model
{
    use SoftDeletes, CommonBooted;

    protected $table = "garments_packing_productions";
    protected $fillable = [
        'factory_id',
        'buyer_id',
        'order_id',
        'purchase_order_id',
        'production_date',
        'packing_ratio',
        'carton_details',
        'color_size_wise_qty_breakdown',
        'colors',
        'sizes',
        'grand_total_cartons',
        'grand_total_n_wt',
        'grand_total_g_wt',
        'grand_total_cbm'
    ];

    protected $casts = [
        'carton_details' => Json::class,
        'color_size_wise_qty_breakdown' => Json::class,
        'colors' => Json::class,
        'sizes' => Json::class
    ];

    public const SOLID_COLOR_SOLID_SIZE = "Solid color solid size";
    public const SOLID_COLOR_ASSORT_SIZE = "solid color asort size";
    public const ASSORT_COLOR_SOLID_SIZE = "Asort color solid size";
    public const ASSORT_COLOR_ASSORT_SIZE = "Asort color asort size";

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
}
