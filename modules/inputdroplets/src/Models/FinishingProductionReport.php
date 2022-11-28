<?php

namespace SkylarkSoft\GoRMG\Inputdroplets\Models;

use Awobaz\Compoships\Compoships;
use Illuminate\Database\Eloquent\Model;
use App\FactoryIdTrait;
use SkylarkSoft\GoRMG\Cuttingdroplets\Models\TotalProductionReport;
use SkylarkSoft\GoRMG\Merchandising\Models\Order;
use SkylarkSoft\GoRMG\Merchandising\Models\PurchaseOrder;
use SkylarkSoft\GoRMG\SystemSettings\Models\Buyer;
use SkylarkSoft\GoRMG\SystemSettings\Models\Color;
use SkylarkSoft\GoRMG\SystemSettings\Models\Factory;
use SkylarkSoft\GoRMG\SystemSettings\Models\Floor;
use SkylarkSoft\GoRMG\SystemSettings\Models\Line;

class FinishingProductionReport extends Model
{
    use FactoryIdTrait, Compoships;

    protected $table = 'finishing_production_reports';

    protected $fillable = [
        'floor_id',
        'line_id',
        'buyer_id',
        'order_id',
        'purchase_order_id',
        'color_id',
        'production_date',
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

    public function todayLiseWiseTarget()
    {
        return $this->hasOne(SewingLineTarget::class, 'line_id', 'line_id')
            ->where('target_date', date('Y-m-d'));
    }

    public function totalProductionReportsByOrderColor()
    {
    	return $this->hasMany(TotalProductionReport::class, ['order_id', 'color_id'], ['order_id', 'color_id']);
    }

    public function orderColorWiseInput()
    {
    	return $this->hasMany(self::class, ['order_id', 'color_id'],['order_id', 'color_id'])->where('sewing_input', '>', 0);
    }

    public function todayOrderColorWiseInput()
    {
    	return $this->hasMany(self::class, ['order_id', 'color_id'],['order_id', 'color_id'])->whereDate('production_date', today()->toDateString())->where('sewing_input', '>', 0);
    }
    /**
     * @param $order_id
     * @param $color_id
     * @param $line_id
     * @return int
     */
    public static function orderColorLineWiseTotalInputQty($order_id, $color_id, $line_id)
    {
        return self::where([
                'order_id' => $order_id,
                'color_id' => $color_id,
                'line_id' => $line_id
            ])->sum('sewing_input') ?? 0;
    }

    public static function orderColorLineWiseQuantities($order_id, $color_id, $line_id) {
        $data = self::where([
            'order_id' => $order_id,
            'color_id' => $color_id,
            'line_id' => $line_id
        ])->selectRaw('SUM(sewing_input) as sewing_input, SUM(sewing_output) as sewing_output, SUM(sewing_rejection) as sewing_rejection')->first();
        return $data;
    }

    public static function orderBuyerWiseTotalInputQty($order_id, $buyer_id)
    {
        return self::where([
                'order_id' => $order_id,
                'buyer_id' => $buyer_id,
            ])->sum('sewing_input') ?? 0;
    }

    /**
     * @param $order_id
     * @param $color_id
     * @param $line_id
     * @return int
     */
    public static function orderColorLineWiseTotalOutputQty($order_id, $color_id, $line_id)
    {
        return self::where([
                'order_id' => $order_id,
                'color_id' => $color_id,
                'line_id' => $line_id
            ])->sum('sewing_output') ?? 0;
    }

    /**
     * @param $order_id
     * @param $color_id
     * @param $line_id
     * @return int
     */
    public static function orderColorLineWiseTotalRejectionQty($order_id, $color_id, $line_id)
    {
        return self::where([
                'order_id' => $order_id,
                'color_id' => $color_id,
                'line_id' => $line_id
            ])->sum('sewing_rejection') ?? 0;
    }

    /**
     * @param $purchase_order_id
     * @param $color_id
     * @param $line_id
     * @return string
     */
    public static function poColorLineWiseFirstInputDate($purchase_order_id, $color_id, $line_id)
    {
        return self::where([
                'purchase_order_id' => $purchase_order_id,
                'color_id' => $color_id,
                'line_id' => $line_id
            ])->where('sewing_input', '>', 0)
                ->orderBy('production_date', 'asc')
                ->first()
                ->production_date ?? null;
    }

    /**
     * @param $purchase_order_id
     * @param $color_id
     * @param $line_id
     * @return string
     */
    public static function poColorLineWiseFirstInputDateWithoutGlobalScopes($purchase_order_id, $color_id, $line_id)
    {
        return self::withoutGlobalScopes()
            ->where([
                'purchase_order_id' => $purchase_order_id,
                'color_id' => $color_id,
                'line_id' => $line_id
            ])->where('sewing_input', '>', 0)
                ->orderBy('production_date', 'asc')
                ->first()
                ->production_date ?? null;
    }

    /**
     * @param $purchase_order_id
     * @param $color_id
     * @param $line_id
     * @param $date
     * @return int
     */
    public static function poColorLineWiseTotalInputQty($purchase_order_id, $color_id, $line_id, $date)
    {
        return self::where([
            'purchase_order_id' => $purchase_order_id,
            'color_id' => $color_id,
            'line_id' => $line_id
        ])->where('production_date', '<=', $date)
            ->sum('sewing_input') ?? 0;
    }

    /**
     * @param $purchase_order_id
     * @param $color_id
     * @param $line_id
     * @param $date
     * @return int
     */
    public static function poColorLineWiseTotalInputQtyWithoutGlobalScopes($purchase_order_id, $color_id, $line_id, $date)
    {
        return self::withoutGlobalScopes()->where([
            'purchase_order_id' => $purchase_order_id,
            'color_id' => $color_id,
            'line_id' => $line_id
        ])->where('production_date', '<=', $date)
            ->sum('sewing_input') ?? 0;
    }

    /**
     * @param $purchase_order_id
     * @param $color_id
     * @param $line_id
     * @param $date
     * @return int
     */
    public static function poColorLineWiseTotalOutputQty($purchase_order_id, $color_id, $line_id, $date)
    {
        return self::where([
            'purchase_order_id' => $purchase_order_id,
            'color_id' => $color_id,
            'line_id' => $line_id
        ])->where('production_date', '<=', $date)
            ->sum('sewing_output') ?? 0;
    }

    /**
     * @param $purchase_order_id
     * @param $color_id
     * @param $line_id
     * @param $date
     * @return int
     */
    public static function poColorLineWiseTotalOutputQtyWithGlobalScopes($purchase_order_id, $color_id, $line_id, $date)
    {
        return self::withoutGlobalScopes()
            ->where([
            'purchase_order_id' => $purchase_order_id,
            'color_id' => $color_id,
            'line_id' => $line_id
        ])->where('production_date', '<=', $date)
            ->sum('sewing_output') ?? 0;
    }

    /**
     * @param $purchase_order_id
     * @param $color_id
     * @param $input_date
     * @param $line_id
     * @return string
     */
    public static function getCuttingInventoryChallanWithoutGlobalScopes($purchase_order_id, $color_id, $input_date, $line_id)
    {
        return CuttingInventoryChallan::withoutGlobalScopes()
                ->leftJoin('cutting_inventories', 'cutting_inventories.challan_no', 'cutting_inventory_challans.challan_no')
                ->leftJoin('bundle_cards', 'bundle_cards.id', 'cutting_inventories.bundle_card_id')
                ->where([
                    'bundle_cards.purchase_order_id' => $purchase_order_id,
                    'bundle_cards.color_id' => $color_id,
                    'cutting_inventory_challans.line_id' => $line_id,
                    'cutting_inventory_challans.factory_id' => factoryId(),
                ])
                ->whereDate('cutting_inventory_challans.input_date', $input_date)
                ->select('cutting_inventory_challans.challan_no', 'cutting_inventory_challans.updated_at')
                ->groupBy('cutting_inventory_challans.challan_no', 'cutting_inventory_challans.updated_at')
                ->orderby('cutting_inventory_challans.challan_no', 'desc')
                ->get() ?? '';
    }

    public static function getTodayFactoryData($factory_id)
    {
        return self::query()
            ->selectRaw('SUM(sewing_input) as sewing_input, SUM(sewing_output) as sewing_output, SUM(sewing_rejection) as sewing_rejection')
            ->where('factory_id', $factory_id)
            ->whereDate('production_date', now()->toDateString())
            ->groupBy('factory_id')
            ->first();
    }

    public static function getTotalLineWip($line_id, $order_ids, $color_ids)
    {
        $query= self::query()->withoutGlobalScope('factoryId')->where('line_id', $line_id)
            ->when(($order_ids && is_array($order_ids) && count($order_ids)), function ($q) use($order_ids) {
                $q->whereIn('order_id', $order_ids);
            })
            ->when(($color_ids && is_array($color_ids) && count($color_ids)), function ($q) use($color_ids) {
                $q->whereIn('color_id', $color_ids);
            });
        $total_input_qty = $query->sum('sewing_input');
        $total_output_qty = $query->sum('sewing_output');
        $total_rejection_qty = $query->sum('sewing_rejection');
        $wip = $total_input_qty - $total_output_qty - $total_rejection_qty;

        return $wip;
    }
}
