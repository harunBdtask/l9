<?php

namespace SkylarkSoft\GoRMG\Iedroplets\Services\Reports;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use SkylarkSoft\GoRMG\Cuttingdroplets\Models\TotalProductionReport;
use SkylarkSoft\GoRMG\Iedroplets\Models\Shipment;
use SkylarkSoft\GoRMG\Inputdroplets\Models\FinishingProductionReport;
use SkylarkSoft\GoRMG\Merchandising\Models\PurchaseOrder;

class WeeklyShipmentScheduleService
{
    public function reportData(Request $request)
    {
        $date = $request->get('date');
        $buyerId = $request->get('buyer_id');
        $startOfWeek = Carbon::parse($date)->startOfWeek()->format('Y-m-d');
        $endOfWeek = Carbon::parse($date)->endOfWeek()->format('Y-m-d');

        $purchaseOrdersId = PurchaseOrder::query()
            ->whereBetween('ex_factory_date', [$startOfWeek, $endOfWeek])
            ->when($buyerId && $buyerId != "null", function (Builder $builder) use ($buyerId) {
                $builder->where('buyer_id', $buyerId);
            })->pluck('id');

        $shipments = Shipment::query()
            ->with(['buyer', 'size', 'purchaseOrder', 'color', 'order'])
            ->whereIn('purchase_order_id', $purchaseOrdersId)
            ->get();

        $purchaseOrders = $shipments->pluck('purchase_order_id');

        $totalProductionReports = TotalProductionReport::query()
            ->whereIn('purchase_order_id', $purchaseOrders)
            ->get();

        $totalFinishingProductionReports = FinishingProductionReport::query()
            ->whereIn('purchase_order_id', $purchaseOrders)
            ->where('sewing_output', '>', 0)
            ->with('line')
            ->get();

        return $shipments->map(function ($shipment) use ($totalProductionReports, $totalFinishingProductionReports) {
            $totalProductions = $totalProductionReports
                ->where('purchase_order_id', $shipment->purchase_order_id);

            $totalFinishingProduction = $totalFinishingProductionReports
                ->where('purchase_order_id', $shipment->purchase_order_id);

            $totalSewingReceiveQty = $totalFinishingProduction->sum('sewing_output');
            $totalPackQty = $totalProductions->sum('total_packing');
            $sewingLines = collect($totalFinishingProduction)
                ->pluck('line.line_no')
                ->unique()
                ->values()
                ->join(', ');

            return [
                'buyer_name' => $shipment->buyer->name,
                'style_name' => $shipment->order->style_name,
                'po_no' => $shipment->purchaseOrder->po_no,
                'ref_no' => $shipment->order->reference_no,
                'order_qty' => $shipment->purchaseOrder->po_quantity,
                'shipment_qty' => $shipment->ship_quantity,
                'total_sewing_receive_qty' => $totalSewingReceiveQty,
                'receive_balance' => $shipment->ship_quantity - $totalSewingReceiveQty,
                'total_pack_qty' => $totalPackQty,
                'final_balance' => $shipment->ship_quantity - $totalPackQty,
                'input_balance' => $shipment->purchaseOrder->po_quantity - $totalSewingReceiveQty,
                'sewing_lines' => $sewingLines,
                'remarks' => $shipment->remarks,
            ];
        });
    }
}
