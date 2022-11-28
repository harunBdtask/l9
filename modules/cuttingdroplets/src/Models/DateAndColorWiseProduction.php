<?php

namespace SkylarkSoft\GoRMG\Cuttingdroplets\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\FactoryIdTrait;

class DateAndColorWiseProduction extends Model
{
    use FactoryIdTrait;
    use SoftDeletes;

    protected $fillable = [
        'buyer_id',
        'order_id',
        'purchase_order_id',
        'color_id',
        'production_date',
        'cutting_qty',
        'cutting_rejection_qty',
        'print_sent_qty',
        'print_received_qty',
        'print_rejection_qty',
        'embroidary_sent_qty',
        'embroidary_received_qty',
        'embroidary_rejection_qty',
        'input_qty',
        'sewing_output_qty',
        'sewing_rejection_qty',
        'washing_sent_qty',
        'washing_received_qty',
        'washing_rejection_qty',
        'poly_qty',
        'poly_rejection',
        'iron_qty',
        'iron_rejection_qty',
        'packing_qty',
        'packing_rejection_qty',
        'received_for_poly',
        'total_cartoon',
        'total_pcs',
        'ship_qty',
        'factory_id'
    ];

    protected $dates = [
        'deleted_at'
    ];

    public function buyer()
    {
        return $this->belongsTo('SkylarkSoft\GoRMG\SystemSettings\Models\Buyer', 'buyer_id');
    }

    public function buyerWithoutGlobalScopes()
    {
        return $this->belongsTo('SkylarkSoft\GoRMG\SystemSettings\Models\Buyer', 'buyer_id')->withoutGlobalScopes();
    }

    public function order()
    {
        return $this->belongsTo('SkylarkSoft\GoRMG\Merchandising\Models\Order', 'order_id');
    }

    public function orderWithoutGlobalScopes()
    {
        return $this->belongsTo('SkylarkSoft\GoRMG\Merchandising\Models\Order', 'order_id')->withoutGlobalScopes();
    }

    public function purchaseOrder()
    {
        return $this->belongsTo('SkylarkSoft\GoRMG\Merchandising\Models\PurchaseOrder', 'purchase_order_id');
    }

    public function purchaseOrderWithoutGlobalScopes()
    {
        return $this->belongsTo('SkylarkSoft\GoRMG\Merchandising\Models\PurchaseOrder', 'purchase_order_id')->withoutGlobalScopes();
    }

    public function color()
    {
        return $this->belongsTo('SkylarkSoft\GoRMG\SystemSettings\Models\Color', 'color_id');
    }

    public function colorWithoutGlobalScopes()
    {
        return $this->belongsTo('SkylarkSoft\GoRMG\SystemSettings\Models\Color', 'color_id')->withoutGlobalScopes();
    }

    public function factory()
    {
        return $this->belongsTo('SkylarkSoft\GoRMG\SystemSettings\Models\Factory', 'factory_id');
    }

    public function factoryWithoutGlobalScopes()
    {
        return $this->belongsTo('SkylarkSoft\GoRMG\SystemSettings\Models\Factory', 'factory_id')->withoutGlobalScopes();
    }

    public static function todaysCuttingProductionOrderColorWise($order_id, $color_id)
    {
        $todays_cutting_production_query = self::where([
            'order_id' => $order_id,
            'color_id' => $color_id,
            'production_date' => date('Y-m-d')
        ]);
        $todays_cutting_production = 0;
        if ($todays_cutting_production_query->count() > 0) {
            $cutting_qty = $todays_cutting_production_query->first()->cutting_qty;
            $cutting_rejection = $todays_cutting_production_query->first()->cutting_rejection_qty;
            $todays_cutting_production = $cutting_qty - $cutting_rejection;
        }
        return $todays_cutting_production;
    }

    public static function todaysPrintSendQtyOrderColorWise($order_id, $color_id)
    {
        $todays_print_send_query = self::where([
            'order_id' => $order_id,
            'color_id' => $color_id,
            'production_date' => date('Y-m-d')
        ]);
        $todays_print_send_qty = 0;
        if ($todays_print_send_query->count() > 0) {
            $todays_print_send_qty = $todays_print_send_query->first()->print_sent_qty;
        }
        return $todays_print_send_qty;
    }

    public static function todaysPrintReceivedQtyOrderColorWise($order_id, $color_id)
    {
        $todays_print_received_query = self::where([
            'order_id' => $order_id,
            'color_id' => $color_id,
            'production_date' => date('Y-m-d')
        ]);
        $todays_print_received_qty = 0;
        if ($todays_print_received_query->count() > 0) {
            $todays_print_received_qty = $todays_print_received_query->first()->print_received_qty;
        }
        return $todays_print_received_qty;
    }

    public static function todaysSewingInputQtyOrderColorWise($order_id, $color_id)
    {
        $todays_sewing_input_query = self::where([
            'order_id' => $order_id,
            'color_id' => $color_id,
            'production_date' => date('Y-m-d')
        ]);
        $todays_sewing_input_qty = 0;
        if ($todays_sewing_input_query->count() > 0) {
            $todays_sewing_input_qty = $todays_sewing_input_query->first()->input_qty;
        }
        return $todays_sewing_input_qty;
    }

    public static function todaysSewingOutputQtyOrderColorWise($order_id, $color_id)
    {
        $todays_sewing_output_query = self::where([
            'order_id' => $order_id,
            'color_id' => $color_id,
            'production_date' => date('Y-m-d')
        ]);
        $todays_sewing_output_qty = 0;
        if ($todays_sewing_output_query->count() > 0) {
            $todays_sewing_output_qty = $todays_sewing_output_query->first()->sewing_output_qty;
        }
        return $todays_sewing_output_qty;
    }

    public static function todaysWashingSendQtyOrderColorWise($order_id, $color_id)
    {
        $todays_washing_send_query = self::where([
            'order_id' => $order_id,
            'color_id' => $color_id,
            'production_date' => date('Y-m-d')
        ]);
        $todays_washing_send_qty = 0;
        if ($todays_washing_send_query->count() > 0) {
            $todays_washing_send_qty = $todays_washing_send_query->first()->washing_sent_qty;
        }
        return $todays_washing_send_qty;
    }

    public static function todaysWashingReceivedQtyOrderColorWise($order_id, $color_id)
    {
        $todays_washing_received_query = self::where([
            'order_id' => $order_id,
            'color_id' => $color_id,
            'production_date' => date('Y-m-d')
        ]);
        $todays_washing_received_qty = 0;
        if ($todays_washing_received_query->count() > 0) {
            $todays_washing_received_qty = $todays_washing_received_query->first()->washing_received_qty;
        }
        return $todays_washing_received_qty;
    }

    public static function todaysPolyQtyOrderColorWise($order_id, $color_id)
    {
        $todays_poly_query = self::where([
            'order_id' => $order_id,
            'color_id' => $color_id,
            'production_date' => date('Y-m-d')
        ]);
        $todays_poly_qty = 0;
        if ($todays_poly_query->count() > 0) {
            $todays_poly_qty = $todays_poly_query->first()->poly_qty;
        }
        return $todays_poly_qty;
    }

    public static function todaysShipQtyOrderColorWise($order_id, $color_id)
    {
        $todays_ship_query = self::where([
            'order_id' => $order_id,
            'color_id' => $color_id,
            'production_date' => date('Y-m-d')
        ]);
        $todays_ship_qty = 0;
        if ($todays_ship_query->count() > 0) {
            $todays_ship_qty = $todays_ship_query->first()->ship_qty;
        }
        return $todays_ship_qty;
    }

    public static function ironQtyDatePurchaseOrderColorWise($date, $purchase_order_id, $color_id)
    {
        return self::where([
            'purchase_order_id' => $purchase_order_id,
            'color_id' => $color_id,
            'production_date' => $date
        ])->sum('iron_qty');
    }

    public static function polyQtyDatePurchaseOrderColorWise($date, $purchase_order_id, $color_id)
    {
        return self::where([
            'purchase_order_id' => $purchase_order_id,
            'color_id' => $color_id,
            'production_date' => $date
        ])->sum('poly_qty');
    }

    public static function packingQtyDatePurchaseOrderColorWise($date, $purchase_order_id, $color_id)
    {
        return self::where([
            'purchase_order_id' => $purchase_order_id,
            'color_id' => $color_id,
            'production_date' => $date
        ])->sum('packing_qty');
    }

    public static function shipQtyDatePurchaseOrderColorWise($date, $purchase_order_id, $color_id)
    {
        return self::where([
            'purchase_order_id' => $purchase_order_id,
            'color_id' => $color_id,
            'production_date' => $date
        ])->sum('ship_qty');
    }
}
