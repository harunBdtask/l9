<?php

namespace SkylarkSoft\GoRMG\Knitting\Services\Reports;

use Carbon\Carbon;
use Illuminate\Http\Request;
use SkylarkSoft\GoRMG\Knitting\Filters\Filter;
use SkylarkSoft\GoRMG\Knitting\Models\FabricSalesOrder;

class OrderStatusReportService
{
    private $buyerId, $salesOrderNo;

    public function __construct(Request $request)
    {
        $this->buyerId = $request->get('buyer_id');
        $this->salesOrderNo = $request->get('sales_order_no');
    }

    public function report()
    {
        return FabricSalesOrder::query()
                    ->where('buyer_id', $this->buyerId)
                    ->when($this->salesOrderNo, Filter::applyFilter('sales_order_no', $this->salesOrderNo))
                    ->with(['buyer:id,name', 'breakdown', 'planInfoMany.knittingPrograms', 'planInfoMany.knittingRollDeliveryChallanDetails.challan'])
                    ->get()
                    ->map(function ($item) {
                        $planInfos = collect($item->planInfoMany);
                        $breakdown = collect($item->breakdown);
                        $totalFabQty = $breakdown->sum('finish_qty');
                        $deliveryQty = $planInfos->pluck('knittingRollDeliveryChallanDetails')->flatten()->sum('challan.delivery_qty');
                        return [
                            'booking_type' => $planInfos->pluck('booking_type')->unique()->implode(', ') ?? '',
                            'style_names' => $planInfos->pluck('style_name')->unique()->implode(', ') ?? '',
                            'body_parts' => $planInfos->pluck('bodyPart.name')->unique()->implode(', ') ?? '',
                            'fabric_descriptions' => $planInfos->pluck('fabric_description')->unique()->implode(', ') ?? '',
                            'fab_gsms' => $planInfos->pluck('fabric_gsm')->unique()->implode(', ') ?? '',
                            'fab_dias' => $planInfos->pluck('fabric_dia')->unique()->implode(', ') ?? '',
                            'color_types' => $planInfos->pluck('colorType.color_types')->unique()->implode(', ') ?? '',
                            'gmt_colors' => $planInfos->pluck('gmt_color')->unique()->implode(', ') ?? '',
                            'item_colors' => $planInfos->pluck('item_color')->unique()->implode(', ') ?? '',
                            'act_fab_qty' => $breakdown->sum('gray_qty'),
                            'process_loss' => $breakdown->sum('process_loss'),
                            'total_fab_qty' => $totalFabQty,
                            'program_qty' => $planInfos->pluck('knittingPrograms')->flatten()->sum('program_qty'),
                            'production_qty' => $planInfos->pluck('knittingPrograms')->flatten()->sum('production_qty'),
                            'delivery_qty' => $deliveryQty,
                            'balance_qty' => $totalFabQty - $deliveryQty,
                        ];
                    });
    }
}
