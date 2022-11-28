<?php

namespace SkylarkSoft\GoRMG\Printembrdroplets\Services;

use SkylarkSoft\GoRMG\Merchandising\Models\PurchaseOrderDetail;
use SkylarkSoft\GoRMG\Printembrdroplets\Models\DateFloorWisePrintEmbrReport;

class DailyPrintEmbrReportService
{
    public function generateReport($date)
    {
        return DateFloorWisePrintEmbrReport::query()
            ->selectRaw("buyer_id, order_id, garments_item_id, color_id, SUM(print_sent_qty) as print_sent_qty, SUM(print_received_qty) as print_received_qty, SUM(print_rejection_qty) as print_rejection_qty, SUM(embroidery_sent_qty) as embroidery_sent_qty, SUM(embroidery_received_qty) as embroidery_received_qty, SUM(embroidery_rejection_qty) as embroidery_rejection_qty")
            ->whereDate('production_date', $date)
            ->where(function ($query) {
                $query->orWhere('print_sent_qty', '>', 0)
                    ->orWhere('print_received_qty', '>', 0)
                    ->orWhere('embroidery_sent_qty', '>', 0)
                    ->orWhere('embroidery_received_qty', '>', 0);
            })
            ->groupBy([
                'buyer_id',
                'order_id',
                'garments_item_id',
                'color_id',
            ])
            ->get()
            ->map(function ($report) use ($date) {
                $data = [
                    'production_date' => $date,
                    'buyer_id' => $report->buyer_id,
                    'order_id' => $report->order_id,
                    'garments_item_id' => $report->garments_item_id,
                    'color_id' => $report->color_id,
                ];
                $prev_qtys = DateFloorWisePrintEmbrReport::getPreviousQtys($data);
                $total_print_sent_qty = $report->print_sent_qty + $prev_qtys['print_sent_qty'];
                $total_print_received_qty = $report->print_received_qty + $prev_qtys['print_received_qty'];
                $print_blance = $total_print_sent_qty - $total_print_received_qty;
                $total_embroidery_sent_qty = $report->embroidery_sent_qty + $prev_qtys['embroidery_sent_qty'];
                $total_embroidery_received_qty = $report->embroidery_received_qty + $prev_qtys['embroidery_received_qty'];
                $embr_balance = $total_embroidery_sent_qty - $total_embroidery_received_qty;
                return [
                    'buyer_id' => $report->buyer_id,
                    'buyer' => $report->buyer,
                    'order_id' => $report->order_id,
                    'order' => $report->order,
                    'garments_item_id' => $report->garments_item_id,
                    'garmentsItem' => $report->garmentsItem,
                    'color_id' => $report->color_id,
                    'color' => $report->color,
                    'order_qty' => PurchaseOrderDetail::getColorWiseOrderQuantity($report->order_id, $report->color_id),
                    'print_sent_qty' => $report->print_sent_qty,
                    'prev_print_sent_qty' => $prev_qtys['print_sent_qty'],
                    'total_print_sent_qty' => $total_print_sent_qty,
                    'print_received_qty' => $report->print_received_qty,
                    'prev_print_received_qty' => $prev_qtys['print_received_qty'],
                    'total_print_received_qty' => $total_print_received_qty,
                    'print_blance' => $print_blance,
                    'print_rejection_qty' => $report->print_rejection_qty,
                    'prev_print_rejection_qty' => $prev_qtys['print_rejection_qty'],
                    'embroidery_sent_qty' => $report->embroidery_sent_qty,
                    'prev_embroidery_sent_qty' => $prev_qtys['embroidery_sent_qty'],
                    'total_embroidery_sent_qty' => $total_embroidery_sent_qty,
                    'embroidery_received_qty' => $report->embroidery_received_qty,
                    'prev_embroidery_received_qty' => $prev_qtys['embroidery_received_qty'],
                    'total_embroidery_received_qty' => $total_embroidery_received_qty,
                    'embr_balance' => $embr_balance,
                    'embroidery_rejection_qty' => $report->embroidery_rejection_qty,
                    'prev_embroidery_rejection_qty' => $prev_qtys['embroidery_rejection_qty'],
                ];
            });
    }
}
