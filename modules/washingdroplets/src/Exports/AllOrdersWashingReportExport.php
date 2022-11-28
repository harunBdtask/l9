<?php

namespace SkylarkSoft\GoRMG\Washingdroplets\Exports;

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
use SkylarkSoft\GoRMG\Cuttingdroplets\Models\TotalProductionReport;

class AllOrdersWashingReportExport implements WithTitle, ShouldAutoSize, WithMapping, WithHeadings, FromQuery, WithEvents, ShouldQueue, WithCustomChunkSize
{
    use Exportable;

    private $grand_order_qty;
    private $grand_total_cutting;
    private $grand_todays_input;
    private $grand_total_input;
    private $grand_todays_output;
    private $grand_total_output;
    private $grand_todays_wash_sent;
    private $grand_total_wash_sent;
    private $grand_todays_wash_received;
    private $grand_total_wash_received;
    private $grand_total_rejection;

    public function __construct()
    {
        $this->grand_order_qty = 0;
        $this->grand_total_cutting = 0;
        $this->grand_todays_input = 0;
        $this->grand_total_input = 0;
        $this->grand_todays_output = 0;
        $this->grand_total_output = 0;
        $this->grand_todays_wash_sent = 0;
        $this->grand_total_wash_sent = 0;
        $this->grand_todays_wash_received = 0;
        $this->grand_total_wash_received = 0;
        $this->grand_total_rejection = 0;
    }

    /**
     * @return string
     */
    public function title(): string
    {
        return 'All PO\'s Wash Report';
    }


    /**
     * @return Builder
     */
    public function query()
    {
        return TotalProductionReport::withoutGlobalScope('factoryId')
            ->selectRaw('total_production_reports.buyer_id, total_production_reports.order_id, total_production_reports.purchase_order_id, total_production_reports.color_id,
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
            orders.order_style_no, orders.repeat_no, orders.order_confirmation_date, buyers.name as buyer_name, purchase_orders.po_no, purchase_orders.po_quantity, purchase_orders.ex_factory_date')
            ->join('orders', 'orders.id', 'total_production_reports.order_id')
            ->join('purchase_orders', 'purchase_orders.id', 'total_production_reports.purchase_order_id')
            ->join('buyers', 'buyers.id', 'total_production_reports.buyer_id')
            ->where('total_production_reports.factory_id', factoryId())
            ->groupBy('total_production_reports.buyer_id', 'total_production_reports.order_id', 'total_production_reports.purchase_order_id', 'total_production_reports.color_id')
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

        $order = $report->order_style_no ?? 'Style';
        if ($report->order_style_no && $report->repeat_no) {
            $order .= '(' . $report->repeat_no . ')';
        }
        $purchase_order_no = $report->po_no ?? '';

        $purchase_order_id = $report->purchase_order_id ?? 0;
        $color_id = $report->color_id;
        $color = $report->color->name ?? '';

        $color_wise_po_qty = \SkylarkSoft\GoRMG\Merchandising\Models\PurchaseOrderDetail::getColorWisePoQuantity($purchase_order_id, $color_id);

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
        $todays_washing_sent = $report->todays_washing_sent_sum ?? 0;
        $total_washing_sent = $report->total_washing_sent_sum ?? 0;
        $todays_washing_received = $report->todays_washing_received_sum ?? 0;
        $total_washing_received = $report->total_washing_received_sum ?? 0;
        $total_washing_rejection = $report->total_washing_rejection_sum ?? 0;

        $total_rejection = ($report->total_cutting_rejection_sum ?? 0) + ($report->total_print_rejection_sum ?? 0) + ($report->total_sewing_rejection_sum ?? 0) + ($report->total_washing_rejection_sum ?? 0);

        $this->grand_order_qty += $color_wise_po_qty ?? 0;
        $this->grand_total_cutting += $report->total_cutting_sum ?? 0;
        $this->grand_todays_input += $report->todays_input_sum ?? 0;
        $this->grand_total_input += $report->total_input_sum ?? 0;
        $this->grand_todays_output += $report->todays_sewing_output_sum ?? 0;
        $this->grand_total_output += $report->total_sewing_output_sum ?? 0;

        $this->grand_todays_wash_sent += $report->todays_washing_sent_sum;
        $this->grand_total_wash_sent += $report->total_washing_sent_sum ?? 0;
        $this->grand_todays_wash_received += $report->todays_washing_received_sum ?? 0;
        $this->grand_total_wash_received += $report->total_washing_received_sum ?? 0;

        $this->grand_total_rejection += ($report->total_cutting_rejection_sum ?? 0) + ($report->total_print_rejection_sum ?? 0) + ($report->total_sewing_rejection_sum ?? 0);
        return [
            $buyer_name ?? '',
            $order ?? '',
            $purchase_order_no ?? '',
            $color ?? '',
            $color_wise_po_qty ?? 0,
            $total_cutting ?? 0,
            $todays_input ?? 0,
            $total_input ?? 0,
            $todays_sewing_output ?? 0,
            $total_sewing_output ?? 0,
            $todays_washing_sent ?? 0,
            $total_washing_sent ?? 0,
            $todays_washing_received ?? 0,
            $total_washing_received ?? 0,
            $total_rejection ?? 0,
        ];
    }

    /**
     * @return array
     */
    public function headings(): array
    {
        return [
            'Buyer',
            'Order/Style No',
            'PO',
            'Color',
            'Color Wise PO Qty',
            'Cut. Production',
            'Today\'s Input',
            'Total Input',
            'Today\'s Output',
            'Total Output',
            'Today\'s W.Sent',
            'Total W.Sent',
            'Today\'s W.Received',
            'Total W.Received',
            'Total Rejection',
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
                    $this->grand_todays_input,
                    $this->grand_total_input,
                    $this->grand_todays_output,
                    $this->grand_total_output,
                    $this->grand_todays_wash_sent,
                    $this->grand_total_wash_sent,
                    $this->grand_todays_wash_received,
                    $this->grand_total_wash_received,
                    $this->grand_total_rejection,
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