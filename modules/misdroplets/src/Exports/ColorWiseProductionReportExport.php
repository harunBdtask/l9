<?php

namespace SkylarkSoft\GoRMG\Misdroplets\Exports;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Database\Query\Builder;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithCustomChunkSize;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Events\AfterSheet;
use SkylarkSoft\GoRMG\Cuttingdroplets\Models\DateAndColorWiseProduction;


class ColorWiseProductionReportExport implements WithTitle, ShouldAutoSize, WithMapping, WithHeadings, FromQuery, WithEvents, ShouldQueue, WithCustomChunkSize
{
    use Exportable;

    private $from_date, $to_date,
        $g_color_wise_order_qty,
        $g_color_wise_order_qty_plus_three_percent,
        $g_todays_cutting_production,
        $g_total_cutting,
        $g_cutting_balance,
        $g_todays_print_send_qty,
        $g_total_print_sent_qty,
        $g_total_print_sent_balance,
        $g_todays_print_received_qty,
        $g_total_print_received_qty,
        $g_total_print_received_balance,
        $g_todays_sewing_input_qty,
        $g_total_input_qty,
        $g_total_input_balance,
        $g_todays_sewing_output_qty,
        $g_total_sewing_output_qty,
        $g_total_sewing_output_balance,
        $g_todays_washing_sent_qty,
        $g_total_washing_sent_qty,
        $g_total_washing_sent_balance,
        $g_todays_washing_received_qty,
        $g_total_washing_received_qty,
        $g_total_washing_received_balance,
        $g_todays_poly_qty,
        $g_total_poly_qty,
        $g_total_poly_balance,
        $g_total_cutting_rejection,
        $g_total_print_rejection,
        $g_total_embr_rejection,
        $g_total_sewing_rejection,
        $g_total_washing_rejection,
        $g_total_finishing_rejection,
        $g_total_rejection,
        $g_todays_ship_qty,
        $g_total_ship_qty,
        $g_total_ship_balance;


    public function __construct($from_date, $to_date)
    {
        $this->from_date = $from_date;
        $this->to_date = $to_date;
        $this->g_color_wise_order_qty = 0;
        $this->g_color_wise_order_qty_plus_three_percent = 0;
        $this->g_todays_cutting_production = 0;
        $this->g_total_cutting = 0;
        $this->g_cutting_balance = 0;
        $this->g_todays_print_send_qty = 0;
        $this->g_total_print_sent_qty = 0;
        $this->g_total_print_sent_balance = 0;
        $this->g_todays_print_received_qty = 0;
        $this->g_total_print_received_qty = 0;
        $this->g_total_print_received_balance = 0;
        $this->g_todays_sewing_input_qty = 0;
        $this->g_total_input_qty = 0;
        $this->g_total_input_balance = 0;
        $this->g_todays_sewing_output_qty = 0;
        $this->g_total_sewing_output_qty = 0;
        $this->g_total_sewing_output_balance = 0;
        $this->g_todays_washing_sent_qty = 0;
        $this->g_total_washing_sent_qty = 0;
        $this->g_total_washing_sent_balance = 0;
        $this->g_todays_washing_received_qty = 0;
        $this->g_total_washing_received_qty = 0;
        $this->g_total_washing_received_balance = 0;
        $this->g_todays_poly_qty = 0;
        $this->g_total_poly_qty = 0;
        $this->g_total_poly_balance = 0;
        $this->g_total_cutting_rejection = 0;
        $this->g_total_print_rejection = 0;
        $this->g_total_embr_rejection = 0;
        $this->g_total_sewing_rejection = 0;
        $this->g_total_washing_rejection = 0;
        $this->g_total_finishing_rejection = 0;
        $this->g_total_rejection = 0;
        $this->g_todays_ship_qty = 0;
        $this->g_total_ship_qty = 0;
        $this->g_total_ship_balance = 0;

    }

    /**
     * @return string
     */
    public function title(): string
    {
        return 'Color Wise Production Report';
    }


    /**
     * @return Builder
     */
    public function query()
    {
        $from_date = $this->from_date;
        $to_date = $this->to_date;

        $report_query = DateAndColorWiseProduction::withoutGlobalScope('factoryId')
            ->select(
                \DB::raw('SUM(if(date_and_color_wise_productions.production_date = curdate(), date_and_color_wise_productions.cutting_qty, 0)) as todays_cutting_production'),
                \DB::raw('SUM(if(date_and_color_wise_productions.production_date = curdate(), date_and_color_wise_productions.print_sent_qty, 0)) as todays_print_send_qty'),
                \DB::raw('SUM(if(date_and_color_wise_productions.production_date = curdate(), date_and_color_wise_productions.print_received_qty, 0)) as todays_print_received_qty'),
                \DB::raw('SUM(if(date_and_color_wise_productions.production_date = curdate(), date_and_color_wise_productions.input_qty, 0)) as todays_sewing_input_qty'),
                \DB::raw('SUM(if(date_and_color_wise_productions.production_date = curdate(), date_and_color_wise_productions.sewing_output_qty, 0)) as todays_sewing_output_qty'),
                \DB::raw('SUM(if(date_and_color_wise_productions.production_date = curdate(), date_and_color_wise_productions.washing_sent_qty, 0)) as todays_washing_sent_qty'),
                \DB::raw('SUM(if(date_and_color_wise_productions.production_date = curdate(), date_and_color_wise_productions.washing_received_qty, 0)) as todays_washing_received_qty'),
                \DB::raw('SUM(if(date_and_color_wise_productions.production_date = curdate(), date_and_color_wise_productions.poly_qty, 0)) as todays_poly_qty'),
                \DB::raw('SUM(if(date_and_color_wise_productions.production_date = curdate(), date_and_color_wise_productions.ship_qty, 0)) as todays_ship_qty')
            )
            ->selectRaw('date_and_color_wise_productions.buyer_id, date_and_color_wise_productions.order_id, date_and_color_wise_productions.purchase_order_id, date_and_color_wise_productions.color_id, 
            sum(date_and_color_wise_productions.cutting_qty) as cutting_qty_sum, 
            sum(date_and_color_wise_productions.cutting_rejection_qty) as cutting_rejection_qty_sum, 
            sum(date_and_color_wise_productions.print_sent_qty) as print_sent_qty_sum, 
            sum(date_and_color_wise_productions.print_received_qty) as print_received_qty_sum, 
            sum(date_and_color_wise_productions.print_rejection_qty) as print_rejection_qty_sum, 
            sum(date_and_color_wise_productions.input_qty) as input_qty_sum, 
            sum(date_and_color_wise_productions.sewing_output_qty) as sewing_output_qty_sum, 
            sum(date_and_color_wise_productions.sewing_rejection_qty) as sewing_rejection_qty_sum, 
            sum(date_and_color_wise_productions.washing_sent_qty) as washing_sent_qty_sum, 
            sum(date_and_color_wise_productions.washing_received_qty) as washing_received_qty_sum, 
            sum(date_and_color_wise_productions.washing_rejection_qty) as washing_rejection_qty_sum, 
            sum(date_and_color_wise_productions.poly_qty) as poly_qty_sum, 
            sum(date_and_color_wise_productions.ship_qty) as ship_qty_sum, 
            orders.style_name, buyers.name as buyer_name, purchase_orders.po_no, colors.name as color_name')
            ->join('colors', 'colors.id', 'date_and_color_wise_productions.color_id')
            ->join('purchase_orders', 'purchase_orders.id', 'date_and_color_wise_productions.purchase_order_id')
            ->join('orders', 'orders.id', 'date_and_color_wise_productions.order_id')
            ->join('buyers', 'buyers.id', 'date_and_color_wise_productions.buyer_id')
            ->whereRaw("date_and_color_wise_productions.production_date >= '$from_date'")
            ->whereRaw("date_and_color_wise_productions.production_date <= '$to_date'")
            ->where('date_and_color_wise_productions.factory_id', factoryId())
            ->groupBy('date_and_color_wise_productions.buyer_id', 'date_and_color_wise_productions.order_id', 'date_and_color_wise_productions.purchase_order_id', 'date_and_color_wise_productions.color_id')
            ->orderBy('date_and_color_wise_productions.purchase_order_id', 'asc')
            ->orderBy('date_and_color_wise_productions.color_id', 'asc');

        return $report_query;
    }

    /**
     * @param mixed $report
     * @return array
     * @internal param mixed $row
     *
     */
    public function map($report): array
    {
        $buyer = $report->buyer_name ?? 'Buyer';

        $order = $report->style_name ?? 'Style';
        $po_no = $report->po_no ?? '';

        $purchase_order_id = $report->purchase_order_id;
        $color_id = $report->color_id;
        $color_name = $report->color_name ?? '';
        $color_wise_order_qty = \SkylarkSoft\GoRMG\Merchandising\Models\PurchaseOrderDetail::getColorWisePoQuantity($purchase_order_id, $color_id);
        $color_wise_order_qty_plus_three_percent = round($color_wise_order_qty + (($color_wise_order_qty * 3) / 100));

        $todays_cutting_production = $report->todays_cutting_production ?? 0;
        $todays_print_send_qty = $report->todays_print_send_qty ?? 0;
        $todays_print_received_qty = $report->todays_print_received_qty ?? 0;
        $todays_sewing_input_qty = $report->todays_sewing_input_qty ?? 0;
        $todays_sewing_output_qty = $report->todays_sewing_output_qty ?? 0;
        $todays_washing_sent_qty = $report->todays_washing_sent_qty ?? 0;
        $todays_washing_received_qty = $report->todays_washing_received_qty ?? 0;
        $todays_poly_qty = $report->todays_poly_qty ?? 0;
        $todays_ship_qty = $report->todays_ship_qty ?? 0;

        $total_cutting = ($report->cutting_qty_sum ?? 0) - ($report->cutting_rejection_qty_sum ?? 0);
        $total_print_sent_qty = $report->print_sent_qty_sum ?? 0;
        $total_print_received_qty = $report->print_received_qty_sum ?? 0;
        $total_input_qty = $report->input_qty_sum ?? 0;
        $total_sewing_output_qty = $report->sewing_output_qty_sum ?? 0;
        $total_washing_sent_qty = $report->washing_sent_qty_sum ?? 0;
        $total_washing_received_qty = $report->washing_received_qty_sum ?? 0;

        $total_cutting_rejection = $report->cutting_rejection_qty_sum ?? 0;
        $total_print_rejection = $report->print_rejection_qty_sum ?? 0;
        $total_embr_rejection = 0;
        $total_sewing_rejection = $report->sewing_rejection_qty_sum ?? 0;
        $total_washing_rejection = $report->washing_rejection_qty_sum ?? 0;
        $total_finishing_rejection = 0;

        $total_poly_qty = $report->poly_qty_sum ?? 0;
        $total_ship_qty = $report->ship_qty_sum ?? 0;

        $cutting_balance = $total_cutting - $color_wise_order_qty_plus_three_percent;
        $total_print_sent_balance = $total_print_sent_qty - $total_cutting;
        $total_print_received_balance = $total_print_received_qty - $total_print_sent_qty;
        $total_input_balance = $total_input_qty - $total_cutting;
        $total_sewing_output_balance = $total_sewing_output_qty - $total_input_qty;
        $total_washing_sent_balance = $total_washing_sent_qty - $total_sewing_output_qty;
        $total_washing_received_balance = $total_washing_received_qty - $total_washing_sent_qty;
        $total_poly_balance = $total_poly_qty - $color_wise_order_qty_plus_three_percent;
        $total_ship_balance = $total_ship_qty - $color_wise_order_qty_plus_three_percent;
        $order_to_ship_percent = $color_wise_order_qty > 0 ? (($total_ship_qty * 100) / $color_wise_order_qty) : 0;
        $cut_to_ship_percent = $total_cutting > 0 ? (($total_ship_qty * 100) / $total_cutting) : 0;
        $total_rejection = $total_cutting_rejection + $total_print_rejection + $total_embr_rejection + $total_sewing_rejection + $total_washing_rejection + $total_finishing_rejection;

        $this->g_total_cutting_rejection += $total_cutting_rejection;
        $this->g_total_print_rejection += $total_print_rejection;
        $this->g_total_embr_rejection += $total_embr_rejection;
        $this->g_total_sewing_rejection += $total_sewing_rejection;
        $this->g_total_washing_rejection += $total_washing_rejection;
        $this->g_total_finishing_rejection += $total_finishing_rejection;
        $this->g_total_rejection += $total_rejection;

        $this->g_color_wise_order_qty += $color_wise_order_qty;
        $this->g_color_wise_order_qty_plus_three_percent += $color_wise_order_qty_plus_three_percent;
        $this->g_total_cutting += $total_cutting;
        $this->g_cutting_balance += $cutting_balance;
        $this->g_total_print_sent_qty += $total_print_sent_qty;
        $this->g_total_print_sent_balance += $total_print_sent_balance;
        $this->g_total_print_received_qty += $total_print_received_qty;
        $this->g_total_print_received_balance += $total_print_received_balance;
        $this->g_total_input_qty += $total_input_qty;
        $this->g_total_input_balance += $total_input_balance;
        $this->g_total_sewing_output_qty += $total_sewing_output_qty;
        $this->g_total_sewing_output_balance += $total_sewing_output_balance;
        $this->g_total_washing_sent_qty += $total_washing_sent_qty;
        $this->g_total_washing_sent_balance += $total_washing_sent_balance;
        $this->g_total_washing_received_qty += $total_washing_received_qty;
        $this->g_total_washing_received_balance += $total_washing_received_balance;
        $this->g_total_poly_qty += $total_poly_qty;
        $this->g_total_poly_balance += $total_poly_balance;
        $this->g_total_ship_qty += $total_ship_qty;
        $this->g_total_ship_balance += $total_ship_balance;

        $this->g_todays_cutting_production += $todays_cutting_production;
        $this->g_todays_print_send_qty += $todays_print_send_qty;
        $this->g_todays_print_received_qty += $todays_print_received_qty;
        $this->g_todays_sewing_input_qty += $todays_sewing_input_qty;
        $this->g_todays_sewing_output_qty += $todays_sewing_output_qty;
        $this->g_todays_washing_sent_qty += $todays_washing_sent_qty;
        $this->g_todays_washing_received_qty += $todays_washing_received_qty;
        $this->g_todays_poly_qty += $todays_poly_qty;
        $this->g_todays_ship_qty += $todays_ship_qty;
        return [
            $buyer,
            $order,
            $po_no,
            $color_name,
            $color_wise_order_qty,
            $color_wise_order_qty_plus_three_percent,
            $todays_cutting_production,
            $total_cutting,
            $cutting_balance,
            $todays_print_send_qty,
            $total_print_sent_qty,
            $total_print_sent_balance,
            $todays_print_received_qty,
            $total_print_received_qty,
            $total_print_received_balance,
            $todays_sewing_input_qty,
            $total_input_qty,
            $total_input_balance,
            $todays_sewing_output_qty,
            $total_sewing_output_qty,
            $total_sewing_output_balance,
            $todays_washing_sent_qty,
            $total_washing_sent_qty,
            $total_washing_sent_balance,
            $todays_washing_received_qty,
            $total_washing_received_qty,
            $total_washing_received_balance,
            $todays_poly_qty,
            $total_poly_qty,
            $total_poly_balance,
            $total_cutting_rejection,
            $total_print_rejection,
            $total_embr_rejection,
            $total_sewing_rejection,
            $total_washing_rejection,
            $total_finishing_rejection,
            $total_rejection,
            $todays_ship_qty,
            $total_ship_qty,
            $total_ship_balance,
            number_format($order_to_ship_percent, 2) . '%',
            number_format($cut_to_ship_percent, 2) . '%',
        ];
    }

    /**
     * @return array
     */
    public function headings(): array
    {
        return [
            'Buyer',
            'Order/Style',
            'PO',
            'Color',
            'Order Qty',
            'Order Qty + 3%',
            'Day Cutting',
            'Total Cutting',
            'Cutting Balance',
            'Day Print Send',
            'Total Print Send',
            'Print Send Balance',
            'Day Print Received',
            'Total Print Received',
            'Print Received Balance',
            'Day Sewing Input',
            'Total Sewing Input',
            'Sewing Input Balance',
            'Day Sewing Output',
            'Total Sewing Output',
            'Sewing Output Balance',
            'Day Wash Send',
            'Total Wash Send',
            'Wash Send Balance',
            'Day Wash Received',
            'Total Wash Received',
            'Wash Received Balance',
            'Day Poly',
            'Total Poly',
            'Poly Balance',
            'Cutting Rejection',
            'Print Rejection',
            'Embroidary Rejection',
            'Sewing Rejection',
            'Washing Rejection',
            'Finishing Rejection',
            'Total Rejection',
            'Day Shipment',
            'Total Shipment',
            'Shipment Balance',
            'Order2Ship(%)',
            'Cut2Ship(%)',
        ];
    }

    /**
     * @return array
     */
    public function registerEvents(): array
    {
        return [
//            BeforeExport::class => function (BeforeExport $event) {
//                $event->writer->setCreator('Skylark Soft LTD');
//            },
            AfterSheet::class => function (AfterSheet $event) {
                $event->sheet->append([
                    'Total',
                    '',
                    '',
                    '',
                    $this->g_color_wise_order_qty,
                    $this->g_color_wise_order_qty_plus_three_percent,
                    $this->g_todays_cutting_production,
                    $this->g_total_cutting,
                    $this->g_cutting_balance,
                    $this->g_todays_print_send_qty,
                    $this->g_total_print_sent_qty,
                    $this->g_total_print_sent_balance,
                    $this->g_todays_print_received_qty,
                    $this->g_total_print_received_qty,
                    $this->g_total_print_received_balance,
                    $this->g_todays_sewing_input_qty,
                    $this->g_total_input_qty,
                    $this->g_total_input_balance,
                    $this->g_todays_sewing_output_qty,
                    $this->g_total_sewing_output_qty,
                    $this->g_total_sewing_output_balance,
                    $this->g_todays_washing_sent_qty,
                    $this->g_total_washing_sent_qty,
                    $this->g_total_washing_sent_balance,
                    $this->g_todays_washing_received_qty,
                    $this->g_total_washing_received_qty,
                    $this->g_total_washing_received_balance,
                    $this->g_todays_poly_qty,
                    $this->g_total_poly_qty,
                    $this->g_total_poly_balance,
                    $this->g_total_cutting_rejection,
                    $this->g_total_print_rejection,
                    $this->g_total_embr_rejection,
                    $this->g_total_sewing_rejection,
                    $this->g_total_washing_rejection,
                    $this->g_total_finishing_rejection,
                    $this->g_total_rejection,
                    $this->g_todays_ship_qty,
                    $this->g_total_ship_qty,
                    $this->g_total_ship_balance,
                ]);
            },
        ];
    }

    /**
     * @return int
     */
    public function chunkSize(): int
    {
        return 10;
        // TODO: Implement chunkSize() method.
    }
}