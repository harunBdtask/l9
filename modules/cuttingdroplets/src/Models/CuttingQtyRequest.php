<?php

namespace SkylarkSoft\GoRMG\Cuttingdroplets\Models;

use App\Casts\Json;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use SkylarkSoft\GoRMG\Merchandising\Models\Order;
use SkylarkSoft\GoRMG\Merchandising\Models\PurchaseOrder;
use SkylarkSoft\GoRMG\SystemSettings\Models\Buyer;
use SkylarkSoft\GoRMG\SystemSettings\Models\Color;

class CuttingQtyRequest extends Model
{
    use SoftDeletes;

    protected $table = 'cutting_qty_requests';
    protected $fillable = [
        'buyer_id',
        'order_id',
        'po_id',
        'item_id',
        'color_id',
        'additional_cut_qty',
        'additional_ex_cut',
        'remarks',
        'is_approved',
    ];

    protected $casts = [
        'additional_cut_qty' => Json::class,
        'additional_ex_cut' => Json::class,
    ];

    public function buyer(): BelongsTo
    {
        return $this->belongsTo(Buyer::class, 'buyer_id')->withDefault();
    }

    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class, 'order_id')->withDefault();
    }

    public function color(): BelongsTo
    {
        return $this->belongsTo(Color::class, 'color_id')->withDefault();
    }

    public function purchaseOrder(): BelongsTo
    {
        return $this->belongsTo(PurchaseOrder::class, 'po_id')->withDefault();
    }
}
