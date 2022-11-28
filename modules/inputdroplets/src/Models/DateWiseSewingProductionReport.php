<?php

namespace SkylarkSoft\GoRMG\Inputdroplets\Models;

use Illuminate\Database\Eloquent\Model;
use App\FactoryIdTrait;
use SkylarkSoft\GoRMG\SystemSettings\Models\Color;
use SkylarkSoft\GoRMG\Merchandising\Models\PurchaseOrder;
use SkylarkSoft\GoRMG\SystemSettings\Models\Factory;
use SkylarkSoft\GoRMG\SystemSettings\Models\Floor;
use SkylarkSoft\GoRMG\SystemSettings\Models\Line;

class DateWiseSewingProductionReport extends Model
{
    use FactoryIdTrait;

    protected $table = "date_wise_sewing_production_reports";

    protected $fillable = [
        'floor_id',
        'line_id',
        'sewing_date',
        'sewing_details',
        'total_sewing_input',
        'total_sewing_output',
        'total_sewing_rejection',
        'factory_id',
    ];

    public function getSewingDetailsAttribute($value)
    {
        return json_decode($value,
            true);
    }

    public function setSewingDetailsAttribute($value)
    {
        $this->attributes['sewing_details'] = json_encode($value);
    }

    public function factory()
    {
        return $this->belongsTo(Factory::class, 'factory_id');
    }

    public function floors()
    {
        return $this->belongsTo(Floor::class, 'floor_id', 'id');
    }

    public function floorsWithoutGlobalScopes()
    {
        return $this->belongsTo(Floor::class, 'floor_id', 'id')->withoutGlobalScopes();
    }

    public function lines()
    {
        return $this->belongsTo(Line::class, 'line_id', 'id');
    }

    public function linesWithoutGlobalScopes()
    {
        return $this->belongsTo(Line::class, 'line_id', 'id')->withoutGlobalScopes();
    }

    public static function getPurchaseOrder($order_id)
    {
        return PurchaseOrder::where('id', $order_id)->first();
    }

    public static function getPurchaseOrderWithoutGlobalScopes($order_id)
    {
        return PurchaseOrder::withoutGlobalScopes()->where('id', $order_id)->first();
    }

    public static function getColor($color_id)
    {
        return Color::where('id', $color_id)->first();
    }

    public static function getColorWithoutGlobalScopes($color_id)
    {
        return Color::withoutGlobalScopes()->where('id', $color_id)->first();
    }

    public static function getCuttingInventoryChallan($purchase_order_id, $color_id, $input_date)
    {
        return CuttingInventoryChallan::where([
                'purchase_order_id' => $purchase_order_id,
                'color_id' => $color_id,
            ])
            ->whereDate('input_date', $input_date)
            ->orderby('id', 'desc')
            ->select('challan_no', 'updated_at')
            ->get() ?? '';
    }

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
}
