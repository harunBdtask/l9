<?php

namespace SkylarkSoft\GoRMG\Sewingdroplets\Exports;

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
use Maatwebsite\Excel\Events\BeforeExport;
use Maatwebsite\Excel\Events\BeforeSheet;
use SkylarkSoft\GoRMG\Cuttingdroplets\Models\TotalProductionReport;


class BuyerWiseSewingOutputReportExport implements WithTitle, ShouldAutoSize, WithMapping, WithHeadings, FromQuery, WithEvents, ShouldQueue, WithCustomChunkSize
{
    use Exportable;

    private $buyer_id;
    private $grand_order_qty;
    private $grand_total_cutting;
    private $grand_cutting_print_wip;
    private $grand_print_sent;
    private $grand_print_received;
    private $grand_print_wip;
    private $grand_todays_input;
    private $grand_total_input;
    private $grand_todays_output;
    private $grand_total_output;
    private $grand_sewing_rejection;
    private $grand_total_rejection;
    private $grand_in_line_wip;

    public function __construct($buyer_id, $order_id)
    {
        $this->buyer_id = $buyer_id;
        $this->order_id = $order_id;
        $this->grand_order_qty = 0;
        $this->grand_total_cutting = 0;
        $this->grand_cutting_print_wip = 0;
        $this->grand_print_sent = 0;
        $this->grand_print_received = 0;
        $this->grand_print_wip = 0;
        $this->grand_todays_input = 0;
        $this->grand_total_input = 0;
        $this->grand_todays_output = 0;
        $this->grand_total_output = 0;
        $this->grand_sewing_rejection = 0;
        $this->grand_total_rejection = 0;
        $this->grand_in_line_wip = 0;
    }

    /**
     * @return string
     */
    public function title(): string
    {
        return 'Booking No Wise Sewing Report';
    }


    /**
     * @return Builder
     */
    public function query()
    {
        $buyer_id = $this->buyer_id;
        $order_id = $this->order_id;

        return TotalProductionReport::with('buyer','order','purchaseOrder')
            ->withoutGlobalScope('factoryId')->selectRaw('
            total_production_reports.buyer_id, 
            total_production_reports.order_id, 
            total_production_reports.purchase_order_id, 
            sum(total_production_reports.todays_cutting) as todays_cutting_sum, 
            sum(total_production_reports.total_cutting) as total_cutting_sum, 
            sum(total_production_reports.todays_cutting_rejection) as todays_cutting_rejection_sum, 
            sum(total_production_reports.total_cutting_rejection) as total_cutting_rejection_sum, 
            sum(total_production_reports.total_sent) as total_sent_sum,            
            sum(total_production_reports.total_received) as total_received_sum, 
            sum(total_production_reports.todays_print_rejection) as todays_print_rejection_sum, 
            sum(total_production_reports.total_print_rejection) as total_print_rejection_sum, 
            sum(total_production_reports.todays_input) as todays_input_sum, 
            sum(total_production_reports.total_input) as total_input_sum, 
            sum(total_production_reports.todays_sewing_output) as todays_sewing_output_sum, 
            sum(total_production_reports.total_sewing_output) as total_sewing_output_sum,           
            sum(total_production_reports.total_sewing_rejection) as total_sewing_rejection_sum,            
            sum(total_production_reports.total_cutting_rejection + total_production_reports.total_print_rejection + total_production_reports.total_sewing_rejection) as total_rejection
        ')
        ->join('orders', 'orders.id', 'total_production_reports.order_id')
        ->join('purchase_orders', 'purchase_orders.id', 'total_production_reports.purchase_order_id')
        ->join('buyers', 'buyers.id', 'total_production_reports.buyer_id')
        ->where('total_production_reports.buyer_id', $buyer_id)
        ->where('total_production_reports.order_id', $order_id)
        ->where('total_production_reports.factory_id', factoryId())
        ->groupBy('total_production_reports.buyer_id', 'total_production_reports.order_id', 'total_production_reports.purchase_order_id')
        ->orderBy('total_production_reports.purchase_order_id', 'desc');
    }

    /**
     * @param mixed $report
     * @return array
     * @internal param mixed $row
     *
     */
    public function map($report): array
    {
        $buyer_name = $report->buyer->name ?? 'Buyer';
        $order = $report->order->style_name ?? 'Style';
        $po_no = $report->purchaseOrder->po_no ?? 'PO';
        $po_quantity = $report->purchaseOrder->po_quantity ?? 0;        
        $total_cutting = $report->total_cutting_sum ?? 0;
        $total_cutting_rejection = $report->total_cutting_rejection_sum ?? 0;
        $total_sent = $report->total_sent_sum ?? 0;
        $total_received = $report->total_received_sum ?? 0;
        $total_print_rejection = $report->total_print_rejection_sum ?? 0;
        $todays_input = $report->todays_input_sum ?? 0;
        $total_input = $report->total_input_sum ?? 0;
        $todays_sewing_output = $report->todays_sewing_output_sum ?? 0;
        $total_sewing_output = $report->total_sewing_output_sum ?? 0;
        $total_sewing_rejection = $report->total_sewing_rejection_sum ?? 0;
        $total_rejection = ($report->total_cutting_rejection_sum ?? 0) + ($report->total_print_rejection_sum ?? 0) + ($report->total_sewing_rejection_sum ?? 0);

        $cutting_print_wip = ($report->total_cutting_sum ?? 0) - ($report->total_sent_sum ?? 0);
        $in_line_wip = ($report->total_input_sum ?? 0) - ($report->total_sewing_output_sum ?? 0);
        $cut_2_sewing_ratio = '';
        if($total_sewing_output > 0 && $total_cutting > 0) {
            $cut_2_sewing_ratio = number_format(($report->total_sewing_output_sum / $report->total_cutting_sum) * 100, 2) . ' %';
        }

        $this->grand_order_qty += $po_quantity ?? 0;
        $this->grand_total_cutting += $report->total_cutting_sum ?? 0;
        $this->grand_cutting_print_wip += ($report->total_cutting_sum ?? 0) - ($report->total_sent_sum ?? 0);
        $this->grand_print_sent += $report->total_sent_sum ?? 0;
        $this->grand_print_received += $report->total_received_sum ?? 0;
        $this->grand_print_wip += ($report->total_sent_sum ?? 0) - ($report->total_received_sum ?? 0);
        $this->grand_todays_input += $report->todays_input_sum ?? 0;
        $this->grand_total_input += $report->total_input_sum ?? 0;
        $this->grand_todays_output += $report->todays_sewing_output_sum ?? 0;
        $this->grand_total_output += $report->total_sewing_output_sum ?? 0;
        $this->grand_sewing_rejection += $report->total_sewing_rejection_sum ?? 0;
        $this->grand_total_rejection += ($report->total_cutting_rejection_sum ?? 0) + ($report->total_print_rejection_sum ?? 0) + ($report->total_sewing_rejection_sum ?? 0);
        $this->grand_in_line_wip += ($report->total_input_sum ?? 0) - ($report->total_sewing_output_sum ?? 0);
        
        return [
            $buyer_name,
            $order,
            $po_no,
            $po_quantity,
            $total_cutting,
            $cutting_print_wip,
            $total_sent,
            $total_received,
            $total_sent - $total_received,
            $todays_input,
            $total_input,
            $todays_sewing_output,
            $total_sewing_output,
            $total_sewing_rejection,
            $total_rejection,
            $in_line_wip,
            $cut_2_sewing_ratio,
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
            'Order Qty',
            'Cutt. Qty',
            'WIP In Cutt./Pt./Embr.',
            'Print Sent',
            'Print Rcv',
            'Print WIP',
            'Today\'s Input',
            'Total Input',
            'Today\'s Output',
            'Total Output',
            'Sewing Rejection',
            'Total Rejection',
            'In_line WIP',
            'Cut 2 Sewing Ratio (%)'
        ];
    }

    /**
     * @return array
     */
    public function registerEvents(): array
    {
        return [
            BeforeExport::class => function (BeforeExport $event) {
                $event->writer->getProperties()->setCreator('Skylark Soft Limited');
            },
            BeforeSheet::class => function (BeforeSheet $event) {
                $event->sheet->append([
                    factoryName(),
                ]);
            },
            AfterSheet::class => function (AfterSheet $event) {
                $event->sheet->append([
                    'Total',
                    '',
                    '',
                    '',
                    $this->grand_order_qty,
                    $this->grand_total_cutting ?? 0,
                    $this->grand_cutting_print_wip ?? 0,
                    $this->grand_print_sent ?? 0,
                    $this->grand_print_received ?? 0,
                    $this->grand_print_wip ?? 0,
                    $this->grand_todays_input ?? 0,
                    $this->grand_total_input ?? 0,
                    $this->grand_todays_output ?? 0,
                    $this->grand_total_output ?? 0,
                    $this->grand_sewing_rejection ?? 0,
                    $this->grand_total_rejection ?? 0,
                    $this->grand_in_line_wip ?? 0,
                    ''
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