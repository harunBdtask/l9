<?php

namespace SkylarkSoft\GoRMG\Cuttingdroplets\Models;

use Awobaz\Compoships\Compoships;
use Illuminate\Database\Eloquent\Model;
use App\FactoryIdTrait;
use Carbon\Carbon;
use SkylarkSoft\GoRMG\Merchandising\Actions\StyleAuditReportAction;
use SkylarkSoft\GoRMG\SystemSettings\Models\GarmentsItem;

class TotalProductionReport extends Model
{
    use FactoryIdTrait, Compoships;

    protected $table = 'total_production_reports';

    protected $fillable = [
        'buyer_id',
        'order_id',
        'garments_item_id',
        'purchase_order_id',
        'color_id',
        'todays_cutting',
        'total_cutting',
        'todays_cutting_rejection',
        'total_cutting_rejection',
        'todays_sent',
        'total_sent',
        'todays_received',
        'total_received',
        'todays_print_rejection',
        'total_print_rejection',
        'todays_embroidary_sent',
        'total_embroidary_sent',
        'todays_embroidary_received',
        'total_embroidary_received',
        'todays_embroidary_rejection',
        'total_embroidary_rejection',
        'todays_input',
        'total_input',
        'todays_sewing_output',
        'total_sewing_output',
        'todays_sewing_rejection',
        'total_sewing_rejection',
        'todays_washing_sent',
        'total_washing_sent',
        'todays_washing_received',
        'total_washing_received',
        'todays_washing_rejection',
        'total_washing_rejection',
        'todays_received_for_poly',
        'total_received_for_poly',
        'todays_poly',
        'todays_poly_rejection',
        'total_poly',
        'total_poly_rejection',
        'todays_iron',
        'todays_iron_rejection',
        'total_iron',
        'total_iron_rejection',
        'todays_packing',
        'todays_packing_rejection',
        'total_packing',
        'total_packing_rejection',
        'todays_cartoon',
        'total_cartoon',
        'todays_pcs',
        'todays_shipment_qty',
        'total_shipment_qty',
        'total_pcs',
        'factory_id',
        'updated_at',
        'created_at',
    ];

    public function buyer()
    {
        return $this->belongsTo('SkylarkSoft\GoRMG\SystemSettings\Models\Buyer', 'buyer_id');
    }

    public function order()
    {
        return $this->belongsTo('SkylarkSoft\GoRMG\Merchandising\Models\Order', 'order_id');
    }

    public function garmentsItem()
    {
        return $this->belongsTo(GarmentsItem::class, 'garments_item_id', 'id')->withDefault();
    }

    public function color()
    {
        return $this->belongsTo('SkylarkSoft\GoRMG\SystemSettings\Models\Color', 'color_id', 'id');
    }

    public function purchaseOrder()
    {
        return $this->belongsTo('SkylarkSoft\GoRMG\Merchandising\Models\PurchaseOrder', 'purchase_order_id');
    }

    public function colors()
    {
        return $this->belongsTo('SkylarkSoft\GoRMG\SystemSettings\Models\Color', 'color_id', 'id');
    }

    public function getTodaysCuttingAttribute($value)
    {
        return $this->resetTodaysValue($value);
    }

    public function getTodaysCuttingRejectionAttribute($value)
    {
        return $this->resetTodaysValue($value);
    }

    public function getTodaysSentAttribute($value)
    {
        return $this->resetTodaysValue($value);
    }

    public function getTodaysReceivedAttribute($value)
    {
        return $this->resetTodaysValue($value);
    }

    public function getTodaysPrintRejectionAttribute($value)
    {
        return $this->resetTodaysValue($value);
    }

    public function getTodaysInputAttribute($value)
    {
        return $this->resetTodaysValue($value);
    }

    public function getTodaysSewingOutputAttribute($value)
    {
        return $this->resetTodaysValue($value);
    }

    public function getTodaysSewingRejectionAttribute($value)
    {
        return $this->resetTodaysValue($value);
    }

    public function getTodaysWashingSentAttribute($value)
    {
        return $this->resetTodaysValue($value);
    }

    public function getTodaysWashingReceivedAttribute($value)
    {
        return $this->resetTodaysValue($value);
    }

    public function getTodaysWashingRejectionAttribute($value)
    {
        return $this->resetTodaysValue($value);
    }

    public function getTodaysReceivedForPlyAttribute($value)
    {
        return $this->resetTodaysValue($value);
    }

    public function getTodaysPlyAttribute($value)
    {
        return $this->resetTodaysValue($value);
    }

    public function getTodaysCartoonAttribute($value)
    {
        return $this->resetTodaysValue($value);
    }

    public function getTodaysPcsAttribute($value)
    {
        return $this->resetTodaysValue($value);
    }

    public function getTodaysPolyRejectionAttribute($value)
    {
        return $this->resetTodaysValue($value);
    }

    protected function resetTodaysValue($value)
    {
        if (blank($this->updated_at) || ($this->updated_at->toDateString() == Carbon::now()->toDateString())) {
            return $value;
        }

        $this->todays_cutting = 0;
        $this->todays_cutting_rejection = 0;
        $this->todays_sent = 0;
        $this->todays_received = 0;
        $this->todays_print_rejection = 0;
        $this->todays_input = 0;
        $this->todays_sewing_output = 0;
        $this->todays_sewing_rejection = 0;
        $this->todays_washing_sent = 0;
        $this->todays_washing_received = 0;
        $this->todays_washing_rejection = 0;
        $this->todays_received_for_poly = 0;
        $this->todays_poly = 0;
        $this->todays_poly_rejection = 0;
        $this->todays_iron = 0;
        $this->todays_iron_rejection = 0;
        $this->todays_packing = 0;
        $this->todays_packing_rejection = 0;
        $this->todays_cartoon = 0;
        $this->todays_pcs = 0;
        $this->save();

        return 0;
    }

    public static function orderColorLineWiseTotalInputQty($order_id, $color_id)
    {
        return self::where([
                'order_id' => $order_id,
                'color_id' => $color_id,
            ])->sum('total_input') ?? 0;
    }

    public static function orderBuyerWiseTotalInputQty($order_id, $buyer_id)
    {
        return self::where([
                'order_id' => $order_id,
                'buyer_id' => $buyer_id,
            ])->sum('total_input') ?? 0;
    }

    public static function purchaseOrderWiseTotalInputQty($purchase_order_id)
    {
        return self::where([
                'purchase_order_id' => $purchase_order_id,
            ])->sum('total_input') ?? 0;
    }

    public static function getColorWiseTotal($purchaseOrderId, $colorId)
    {
        return self::where(['purchase_order_id' => $purchaseOrderId, 'color_id' => $colorId])->first();
    }

    public static function booted()
    {
        static::created(function ($model) {
            (new StyleAuditReportAction())
                ->init($model->order_id)
                ->handleOrder()
                ->handleProduction()
                ->saveOrUpdate();
        });
        static::updated(function ($model) {
            (new StyleAuditReportAction())
                ->init($model->order_id)
                ->handleOrder()
                ->handleProduction()
                ->saveOrUpdate();
        });
        static::deleted(function ($model) {
            (new StyleAuditReportAction())
                ->init($model->order_id)
                ->handleOrder()
                ->handleProduction()
                ->saveOrUpdate();
        });
    }
}
