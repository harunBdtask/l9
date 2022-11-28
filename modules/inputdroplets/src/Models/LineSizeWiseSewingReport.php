<?php

namespace SkylarkSoft\GoRMG\Inputdroplets\Models;

use Illuminate\Database\Eloquent\Model;
use App\FactoryIdTrait;
use SkylarkSoft\GoRMG\Merchandising\Models\Order;
use SkylarkSoft\GoRMG\Merchandising\Models\PurchaseOrder;
use SkylarkSoft\GoRMG\SystemSettings\Models\Buyer;
use SkylarkSoft\GoRMG\SystemSettings\Models\Color;
use SkylarkSoft\GoRMG\SystemSettings\Models\Factory;
use SkylarkSoft\GoRMG\SystemSettings\Models\Floor;
use SkylarkSoft\GoRMG\SystemSettings\Models\Line;
use SkylarkSoft\GoRMG\SystemSettings\Models\Size;

class LineSizeWiseSewingReport extends Model
{
    use FactoryIdTrait;

    protected $table = "line_size_wise_sewing_reports";

    protected $fillable = [
        'production_date',
        'floor_id',
        'line_id',
        'buyer_id',
        'order_id',
        'purchase_order_id',
        'color_id',
        'size_id',
        'sewing_input',
        'sewing_output',
        'sewing_rejection',
        'factory_id'
    ];

    public function buyer()
    {
        return $this->belongsTo(Buyer::class, 'buyer_id')->withDefault();
    }

    public function buyerWithoutGlobalScopes()
    {
        return $this->belongsTo(Buyer::class, 'buyer_id')->withDefault()->withoutGlobalScopes();
    }

    public function order()
    {
        return $this->belongsTo(Order::class, 'order_id')->withDefault();
    }

    public function orderWithoutGlobalScopes()
    {
        return $this->belongsTo(Order::class, 'order_id')->withDefault()->withoutGlobalScopes();
    }

    public function purchaseOrder()
    {
        return $this->belongsTo(PurchaseOrder::class, 'purchase_order_id')->withDefault();
    }

    public function purchaseOrderWithoutGlobalScopes()
    {
        return $this->belongsTo(PurchaseOrder::class, 'purchase_order_id')->withDefault()->withoutGlobalScopes();
    }

    public function color()
    {
        return $this->belongsTo(Color::class, 'color_id')->withDefault();
    }

    public function colorWithoutGlobalScopes()
    {
        return $this->belongsTo(Color::class, 'color_id')->withDefault()->withoutGlobalScopes();
    }

    public function size()
    {
        return $this->belongsTo(Size::class, 'size_id')->withDefault();
    }

    public function sizeWithoutGlobalScopes()
    {
        return $this->belongsTo(Size::class, 'size_id')->withDefault()->withoutGlobalScopes();
    }

    public function floor()
    {
        return $this->belongsTo(Floor::class, 'floor_id')->withDefault();
    }

    public function floorWithoutGlobalScopes()
    {
        return $this->belongsTo(Floor::class, 'floor_id')->withDefault()->withoutGlobalScopes();
    }

    public function line()
    {
        return $this->belongsTo(Line::class, 'line_id')->withDefault();
    }

    public function lineWithoutGlobalScopes()
    {
        return $this->belongsTo(Line::class, 'line_id')->withDefault()->WithoutGlobalScopes();
    }

    public function factory()
    {
        return $this->belongsTo(Factory::class, 'factory_id')->withDefault();
    }
}
