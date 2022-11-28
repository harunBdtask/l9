<?php

namespace SkylarkSoft\GoRMG\Cuttingdroplets\Services;

use Illuminate\Support\Facades\DB;
use SkylarkSoft\GoRMG\Cuttingdroplets\Models\TotalProductionReport;

class CuttingProductionReportV2Service
{
    protected $buyer_id;

    public function __construct($buyer_id)
    {
        $this->buyer_id = $buyer_id;
    }

    public function generateReport()
    {
        $sumQueries = ['SUM(todays_cutting) AS todays_cutting_sum',
            'SUM(total_cutting) AS total_cutting_sum',
            'SUM(total_cutting_rejection) AS total_cutting_rejection_sum',
            'SUM((total_cutting - total_cutting_rejection)) AS ok_cutting_sum',
            'SUM(todays_sent) AS todays_print_sent_sum',
            'SUM(total_sent) AS total_print_sent_sum',
            'SUM(todays_embroidary_sent) AS todays_embroidary_sent_sum',
            'SUM(total_embroidary_sent) AS total_embroidary_sent_sum',
            'SUM(todays_received) AS todays_received_sum',
            'SUM(total_received) AS total_received_sum',
            'SUM(todays_embroidary_received) AS todays_embroidary_received_sum',
            'SUM(total_embroidary_received) AS total_embroidary_received_sum',
            'SUM(todays_input) AS todays_input_sum',
            'SUM(total_input) AS total_input_sum'];

        $sumQueriesString = implode(',', $sumQueries);

        $totalProductionReports = TotalProductionReport::query()
            ->with(['buyer', 'order.dealingMerchant', 'garmentsItem', 'color'])
            ->select('*', DB::raw($sumQueriesString))
            ->where('buyer_id', $this->buyer_id)
            ->groupBy(['buyer_id', 'order_id', 'garments_item_id', 'color_id'])
            ->get();

        return $this->formatProductionData($totalProductionReports)->toArray();
    }

    public function formatProductionData($productionReportData)
    {
        return $productionReportData->map(function ($productionReport) {

            $cutting_balance = ($productionReport->ok_cutting_sum
                    - (($productionReport->order->pq_qty_sum * (3 / 100))
                        + $productionReport->order->pq_qty_sum)) ?? 0;

            $print_send_balance = ($productionReport->ok_cutting_sum - $productionReport->total_print_sent_sum) ?? 0;
            $embr_send_balance = ($productionReport->ok_cutting_sum - $productionReport->total_embroidary_sent_sum) ?? 0;
            $print_received_balance = ($productionReport->total_print_sent_sum - $productionReport->total_received_sum) ?? 0;
            $embr_received_balance = ($productionReport->total_embroidary_sent_sum - $productionReport->total_embroidary_received_sum) ?? 0;
            $input_balance = ($productionReport->ok_cutting_sum - $productionReport->total_input_sum) ?? 0;

            return [
                'buyer' => $productionReport->buyer->name,
                'merchant_name' => $productionReport->order->dealingMerchant->screen_name,
                'item' => $productionReport->garmentsItem->name,
                'style_name' => $productionReport->order->style_name,
                'ref_no' => $productionReport->order->reference_no,
                'fabric_type' => $productionReport->order->fabrication,
                'color' => $productionReport->color->name,
                'order_qty' => $productionReport->order->pq_qty_sum,
                'today_cutting' => $productionReport->todays_cutting_sum,
                'total_cutting' => $productionReport->total_cutting_sum,
                'total_cutting_rejection' => $productionReport->total_cutting_rejection,
                'ok_cutting_qty' => $productionReport->ok_cutting_sum,
                'cutting_balance' => $cutting_balance,
                'today_print_send' => $productionReport->todays_print_sent_sum,
                'total_print_send' => $productionReport->total_print_sent_sum,
                'print_send_balance' => $print_send_balance,
                'today_embr_send' => $productionReport->todays_embroidary_sent_sum,
                'total_embr_send' => $productionReport->total_embroidary_sent_sum,
                'embr_send_balance' => $embr_send_balance,
                'today_print_received' => $productionReport->todays_received_sum,
                'total_print_received' => $productionReport->total_received_sum,
                'print_received_balance' => $print_received_balance,
                'today_embr_received' => $productionReport->todays_embroidary_received_sum,
                'total_embr_received' => $productionReport->total_embroidary_received_sum,
                'embr_received_balance' => $embr_received_balance,
                'today_input' => $productionReport->todays_input_sum,
                'total_input' => $productionReport->total_input_sum,
                'input_balance' => $input_balance,
            ];
        });
    }
}
