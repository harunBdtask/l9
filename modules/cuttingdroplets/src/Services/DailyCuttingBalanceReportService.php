<?php

namespace SkylarkSoft\GoRMG\Cuttingdroplets\Services;

use DB;
use SkylarkSoft\GoRMG\Cuttingdroplets\Models\DateTableWiseCutProductionReport;
use SkylarkSoft\GoRMG\Cuttingdroplets\Models\TotalProductionReport;
use SkylarkSoft\GoRMG\Merchandising\Models\Order;
use SkylarkSoft\GoRMG\Merchandising\Models\PurchaseOrderDetail;
use SkylarkSoft\GoRMG\SystemSettings\Models\Size;

class DailyCuttingBalanceReportService
{
    public function resetTodaysDdata()
    {
        return TotalProductionReport::whereDate('updated_at', '!=', now()->toDateString())
            ->update([
                'todays_cutting' => 0,
                'todays_cutting_rejection' => 0,
                'todays_sent' => 0,
                'todays_received' => 0,
                'todays_print_rejection' => 0,
                'todays_embroidary_sent' => 0,
                'todays_embroidary_received' => 0,
                'todays_embroidary_rejection' => 0,
                'todays_input' => 0,
                'todays_sewing_output' => 0,
                'todays_sewing_rejection' => 0,
                'todays_washing_sent' => 0,
                'todays_washing_received' => 0,
                'todays_washing_rejection' => 0,
                'todays_received_for_poly' => 0,
                'todays_poly' => 0,
                'todays_poly_rejection' => 0,
                'todays_cartoon' => 0,
                'todays_pcs' => 0,
                'todays_shipment_qty' => 0
            ]);
    }
    /**
     * @return array
     */
    public function report()
    {
        return TotalProductionReport::query()
            ->where('todays_cutting', '>', 0)
            ->orWhere('todays_sent', '>', 0)
            ->orWhere('todays_received', '>', 0)
            ->orWhere('todays_embroidary_sent', '>', 0)
            ->orWhere('todays_embroidary_received', '>', 0)
            ->orWhere('todays_input', '>', 0)
            ->with(['buyer:id,name'])
            ->selectRaw(
                'buyer_id,
                sum(todays_cutting) as todays_cutting,
                sum(todays_cutting_rejection) as todays_cutting_rejection,
                sum(total_cutting) as total_cutting,
                sum(total_cutting_rejection) as total_cutting_rejection,
                sum(todays_sent) as todays_sent,
                sum(total_sent) as total_sent,
                sum(todays_received) as todays_received,
                sum(total_received) as total_received,
                sum(todays_embroidary_sent) as todays_embroidary_sent,
                sum(total_embroidary_sent) as total_embroidary_sent,
                sum(todays_embroidary_received) as todays_embroidary_received,
                sum(total_embroidary_received) as total_embroidary_received,
                sum(todays_input) as todays_input,
                sum(total_input) as total_input',
            )
            ->groupBy('buyer_id')
            ->get()
            ->map(function ($data) {
                $totalCutting = ((int)$data->total_cutting - (int)$data->total_cutting_rejection) ?? 0;
                $orderQty = Order::query()
                    ->where('buyer_id', $data->buyer_id)
                    ->with('purchaseOrders')
                    ->get()
                    ->pluck('purchaseOrders')
                    ->flatten(1)
                    ->sum('po_quantity');
                return [
                    'buyer_name' => $data->buyer->name ?? '',
                    'order_qty' => $orderQty,
                    'todays_cutting' => (int)$data->todays_cutting - (int)$data->todays_cutting_rejection,
                    'total_cutting' => $totalCutting,
                    'todays_print_send' => $data->todays_sent,
                    'total_print_send' => $data->total_sent,
                    'print_send_balance' => ($totalCutting - (int)$data->total_sent) ?? 0,
                    'todays_print_receive' => $data->todays_received,
                    'total_print_receive' => $data->total_received,
                    'print_receive_balance' => ((int)$data->total_sent - (int)$data->total_received) ?? 0,
                    'todays_embroidary_sent' => $data->todays_embroidary_sent,
                    'total_embroidary_sent' => $data->total_embroidary_sent,
                    'embroidary_sent_balance' => ((int)$data->total_cutting - (int)$data->total_embroidary_sent) ?? 0,
                    'todays_embroidary_received' => $data->todays_embroidary_received,
                    'total_embroidary_received' => $data->total_embroidary_received,
                    'embroidary_receive_balance' => ((int)$data->total_embroidary_sent - (int)$data->total_embroidary_received) ?? 0,
                    'todays_input' => $data->todays_input,
                    'total_input' => $data->total_input,
                    'total_input_balance' => ((int)$data->total_cutting - (int)$data->total_input) ?? 0,
                    'left_cutting' => $orderQty - $totalCutting,
                    'remarks' => null,
                ];
            });
    }
}
