<?php

namespace SkylarkSoft\GoRMG\Printembrdroplets\Exports;

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
use SkylarkSoft\GoRMG\Cuttingdroplets\Models\TotalProductionReport;

class BuyerWisePrintReportExport implements WithTitle, ShouldAutoSize, WithMapping, WithHeadings, FromQuery, WithEvents, ShouldQueue, WithCustomChunkSize
{
    use Exportable;

    private $buyer_id;
    private $grand_order_qty;
    private $grand_total_cutting;
    private $grand_cutting_wip;
    private $grand_total_send;
    private $grand_total_received;
    private $grand_fabric_rejection;
    private $grand_print_rejection;
    private $grand_print_wip_short;

    public function __construct($buyer_id)
    {
        $this->buyer_id = $buyer_id;
        $this->grand_order_qty = 0;
        $this->grand_total_cutting = 0;
        $this->grand_cutting_wip = 0;
        $this->grand_total_send = 0;
        $this->grand_total_received = 0;
        $this->grand_fabric_rejection = 0;
        $this->grand_print_rejection = 0;
        $this->grand_print_wip_short = 0;
    }

    /**
     * @return string
     */
    public function title(): string
    {
        return 'Buyer Wise Print Report';
    }


    /**
     * @return Builder
     */
    public function query()
    {
        $buyer_id = $this->buyer_id;

        return TotalProductionReport::withoutGlobalScope('factoryId')
            ->selectRaw('total_production_reports.buyer_id, total_production_reports.order_id, total_production_reports.purchase_order_id, 
            sum(total_production_reports.todays_cutting) as todays_cutting_sum, 
            sum(total_production_reports.total_cutting) as total_cutting_sum, 
            sum(total_production_reports.todays_cutting_rejection) as todays_cutting_rejection_sum, 
            sum(total_production_reports.total_cutting_rejection) as total_cutting_rejection_sum, 
            sum(total_production_reports.todays_sent) as todays_sent_sum, 
            sum(total_production_reports.total_sent) as total_sent_sum, 
            sum(total_production_reports.todays_received) as todays_received_sum, 
            sum(total_production_reports.total_received) as total_received_sum, 
            sum(total_production_reports.todays_print_rejection) as todays_print_rejection_sum, 
            sum(total_production_reports.total_print_rejection) as total_print_rejection_sum, 
            sum(total_production_reports.todays_input) as todays_input_sum, 
            sum(total_production_reports.total_input) as total_input_sum, 
            sum(total_production_reports.todays_sewing_output) as todays_sewing_output_sum, 
            sum(total_production_reports.total_sewing_output) as total_sewing_output_sum, 
            sum(total_production_reports.todays_sewing_rejection) as todays_sewing_rejection_sum, 
            sum(total_production_reports.total_sewing_rejection) as total_sewing_rejection_sum, 
            sum(total_production_reports.todays_washing_sent) as todays_washing_sent_sum, 
            sum(total_production_reports.total_washing_sent) as total_washing_sent_sum, 
            sum(total_production_reports.todays_washing_received) as todays_washing_received_sum, 
            sum(total_production_reports.total_washing_received) as total_washing_received_sum, 
            sum(total_production_reports.todays_washing_rejection) as todays_washing_rejection_sum, 
            sum(total_production_reports.total_washing_rejection) as total_washing_rejection_sum, 
            orders.style_name, buyers.name as buyer_name, purchase_orders.po_no, purchase_orders.po_quantity, purchase_orders.ex_factory_date')
            ->join('orders', 'orders.id', 'total_production_reports.order_id')
            ->join('purchase_orders', 'purchase_orders.id', 'total_production_reports.purchase_order_id')
            ->join('buyers', 'buyers.id', 'total_production_reports.buyer_id')
            ->where('total_production_reports.buyer_id', $buyer_id)
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
        $buyer_name = $report->buyer_name ?? 'Buyer';
        $booking_no = $report->style_name ?? 'Style';
        $po_no = $report->po_no ?? '';
        $po_quantity = $report->po_quantity ?? 0;
        
        $total_cutting = $report->total_cutting_sum ?? 0;
        $total_cutting_rejection = $report->total_cutting_rejection_sum ?? 0;
        $total_sent = $report->total_sent_sum ?? 0;
        $total_received = $report->total_received_sum ?? 0;
        $total_print_rejection = $report->total_print_rejection_sum ?? 0;

        $cutting_wip = ($report->total_cutting_sum ?? 0) - ($report->total_sent_sum ?? 0);
        $print_wip = ($report->total_sent_sum ?? 0) - ($report->total_received_sum ?? 0);

        $this->grand_order_qty += $po_quantity ?? 0;
        $this->grand_total_cutting += $report->total_cutting_sum ?? 0;
        $this->grand_cutting_wip += ($report->total_cutting_sum ?? 0) - ($report->total_sent_sum ?? 0);
        $this->grand_total_send += $report->total_sent_sum ?? 0;
        $this->grand_total_received += $report->total_received_sum ?? 0;
        $this->grand_fabric_rejection += $report->total_cutting_rejection_sum ?? 0;
        $this->grand_print_rejection += $report->total_print_rejection_sum ?? 0;
        $this->grand_print_wip_short += ($report->total_sent_sum ?? 0) - ($report->total_received_sum ?? 0);

        return [
            $buyer_name ?? '',
            $booking_no,
            $po_no ?? '',
            $po_quantity ?? 0,
            $total_cutting ?? 0,
            $cutting_wip ?? 0,
            $total_sent ?? 0,
            $total_received ?? 0,
            $total_cutting_rejection ?? 0,
            $total_print_rejection ?? 0,
            $print_wip ?? 0,
        ];
    }

    /**
     * @return array
     */
    public function headings(): array
    {
        return [
            'Buyer',
            'Style',
            'PO',
            'PO Quantity',
            'Cutting Production',
            'Cutting WIP',
            'Total Send',
            'Total Recieved',
            'Fabric Rejection',
            'Print Rejection',
            'Print WIP/Short',
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
                    $this->grand_order_qty,
                    $this->grand_total_cutting,
                    $this->grand_cutting_wip,
                    $this->grand_total_send,
                    $this->grand_total_received,
                    $this->grand_fabric_rejection,
                    $this->grand_print_rejection,
                    $this->grand_print_wip_short,
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