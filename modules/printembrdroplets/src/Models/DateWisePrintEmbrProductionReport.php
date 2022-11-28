<?php

namespace SkylarkSoft\GoRMG\Printembrdroplets\Models;

use App\FactoryIdTrait;
use Illuminate\Database\Eloquent\Model;

class DateWisePrintEmbrProductionReport extends Model
{
    use FactoryIdTrait;

    protected $table = "date_wise_print_embr_production_reports";

    protected $fillable = [
        'production_date',
        'buyer_id',
        'order_id',
        'purchase_order_id',
        'color_id',
        'size_id',
        'print_sent_qty',
        'print_received_qty',
        'print_rejection_qty',
        'embroidery_sent_qty',
        'embroidery_received_qty',
        'embroidery_rejection_qty',
        'factory_id'
    ];

    protected $dates = [
        'deleted_at'
    ];

    public function buyer()
    {
        return $this->belongsTo('SkylarkSoft\GoRMG\SystemSettings\Models\Buyer', 'buyer_id')->withDefault();
    }

    public function buyerWithoutGlobalScopes()
    {
        return $this->belongsTo('SkylarkSoft\GoRMG\SystemSettings\Models\Buyer', 'buyer_id')->withoutGlobalScopes();
    }

    public function order()
    {
        return $this->belongsTo('SkylarkSoft\GoRMG\Merchandising\Models\Order', 'order_id')->withDefault();
    }

    public function orderWithoutGlobalScopes()
    {
        return $this->belongsTo('SkylarkSoft\GoRMG\Merchandising\Models\Order', 'order_id')->withoutGlobalScopes();
    }

    public function purchaseOrder()
    {
        return $this->belongsTo('SkylarkSoft\GoRMG\Merchandising\Models\PurchaseOrder', 'purchase_order_id')->withDefault();
    }

    public function purchaseOrderWithoutGlobalScopes()
    {
        return $this->belongsTo('SkylarkSoft\GoRMG\Merchandising\Models\PurchaseOrder', 'purchase_order_id')->withoutGlobalScopes();
    }

    public function color()
    {
        return $this->belongsTo('SkylarkSoft\GoRMG\SystemSettings\Models\Color', 'color_id')->withDefault();
    }

    public function colorWithoutGlobalScopes()
    {
        return $this->belongsTo('SkylarkSoft\GoRMG\SystemSettings\Models\Color', 'color_id')->withoutGlobalScopes();
    }

    public function size()
    {
        return $this->belongsTo('SkylarkSoft\GoRMG\SystemSettings\Models\Size', 'size_id')->withDefault();
    }

    public function sizeWithoutGlobalScopes()
    {
        return $this->belongsTo('SkylarkSoft\GoRMG\SystemSettings\Models\Size', 'size_id')->withoutGlobalScopes();
    }

    public function factory()
    {
        return $this->belongsTo('SkylarkSoft\GoRMG\SystemSettings\Models\Factory', 'factory_id');
    }

    public function factoryWithoutGlobalScopes()
    {
        return $this->belongsTo('SkylarkSoft\GoRMG\SystemSettings\Models\Factory', 'factory_id')->withoutGlobalScopes();
    }

    public static function getTodaysFactoryData($factory_id)
    {
        return DateWisePrintEmbrProductionReport::query()
            ->selectRaw("SUM(print_sent_qty) as print_sent_qty, SUM(print_received_qty) as print_received_qty, SUM(print_rejection_qty) as print_rejection_qty, SUM(embroidery_sent_qty) as embroidery_sent_qty, SUM(embroidery_received_qty) as embroidery_received_qty, SUM(embroidery_rejection_qty) as embroidery_rejection_qty")
            ->where('factory_id', $factory_id)
            ->whereDate('production_date', now()->toDateString())
            ->groupBy('factory_id')
            ->first();
    }
}
