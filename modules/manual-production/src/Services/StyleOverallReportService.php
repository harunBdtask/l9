<?php

namespace SkylarkSoft\GoRMG\ManualProduction\Services;

use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use SkylarkSoft\GoRMG\ManualProduction\Models\Reports\ManualTotalProductionReport;
use SkylarkSoft\GoRMG\Merchandising\Models\Order;
use SkylarkSoft\GoRMG\Merchandising\Models\PurchaseOrderDetail;
use SkylarkSoft\GoRMG\SystemSettings\Models\Buyer;

class StyleOverallReportService
{
    public static function getReport(Request $request): array
    {
        $buyers = Buyer::query()->where('factory_id', factoryId())->pluck('name as text', 'id');
        $buyer_id = $request->input('buyer_id');
        $order_id = $request->input('order_id');
        $order = null;
        $reports = [];
        $orders = [];
        if ($order_id) {
            $orders = Order::query()->where('id', $order_id)->pluck('style_name', 'id');
        }
        if ($request->isMethod('post')) {
            $request->validate([
                'buyer_id' => 'required',
                'order_id' => 'required',
            ]);
            $poDetails = PurchaseOrderDetail::query()->selectRaw("*,SUM(quantity) as total_order_qty")
                ->where('order_id', $order_id)->groupBy('color_id')->get();
            $manual_total_production = ManualTotalProductionReport::query()->selectRaw("
                order_id, color_id,
                SUM(cutting_qty) AS total_cutting_sum,
                SUM(cutting_rejection_qty) AS total_cutting_rejection_sum,
                SUM(print_sent_qty) AS total_print_sent_sum,
                SUM(print_receive_qty) AS total_print_receive_sum,
                SUM(embroidery_sent_qty) AS total_embroidery_sent_sum,
                SUM(embroidery_receive_qty) AS total_embroidery_receive_sum,
                SUM(input_qty) AS total_input_sum,
                SUM(sewing_output_qty) AS total_output_sum
            ")->where('order_id', $order_id)->groupBy('color_id')->get();
            foreach ($poDetails as $value) {
                $color_id = $value->color_id;
                $report = $manual_total_production->where('order_id', $order_id)->where('color_id', $color_id)->first();
                $order_qty_plus_five_percent = $value->total_order_qty + round(($value->total_order_qty * 5) / 100);
                $total_cutting = Arr::get($report, 'total_cutting_sum', 0);
                $cut_balance = $order_qty_plus_five_percent - $total_cutting ?? 0;
                $input_qty = Arr::get($report, 'total_input_sum', 0);
                $output_qty = Arr::get($report, 'total_output_sum', 0);
                $cut_percent = $order_qty_plus_five_percent > 0 ? number_format(($total_cutting / $order_qty_plus_five_percent) * 100, 2) : 0;
                $input_percent = $total_cutting > 0 ? number_format(($input_qty / $total_cutting) * 100, 2) : 0;
                $reports[] = [
                    'order' => Arr::get($value, 'order.color', null),
                    'color' => Arr::get($value, 'color.name', null),
                    'order_qty' => Arr::get($value, 'total_order_qty', 0),
                    'order_qty_plus_five_percent' => $order_qty_plus_five_percent,
                    'total_cutting' => $total_cutting,
                    'cutting_balance' => $cut_balance,
                    'cutting_percent' => $cut_percent,
                    'print_send' => Arr::get($report, 'total_print_sent_sum', 0),
                    'print_receive' => Arr::get($report, 'total_print_receive_sum', 0),
                    'emb_send' => Arr::get($report, 'total_embroidery_sent_sum', 0),
                    'emb_receive' => Arr::get($report, 'total_embroidery_receive_sum', 0),
                    'input_qty' => $input_qty,
                    'output_qty' => $output_qty,
                    'input_percent' => $input_percent
                ];
            }
            $order = Order::query()->findOrFail($order_id)['style_name'];
        }

        return [
            $reports,
            $buyer_id,
            $order_id,
            $buyers,
            $order,
            $orders
        ];
    }
}
