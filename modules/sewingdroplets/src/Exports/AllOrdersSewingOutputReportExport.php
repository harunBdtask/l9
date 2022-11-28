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

class AllOrdersSewingOutputReportExport implements WithTitle, ShouldAutoSize, WithMapping, WithHeadings, FromQuery, WithEvents, ShouldQueue, WithCustomChunkSize
{
    use Exportable;

    private $grand_order_qty;
    private $grand_total_cutting;
    private $grand_cutting_print_wip;
    private $grand_print_sent;
    private $grand_todays_input;
    private $grand_total_input;
    private $grand_todays_output;
    private $grand_total_output;
    private $grand_sewing_rejection;
    private $grand_total_rejection;
    private $grand_in_line_wip;
    private $order_id;

    public function __construct($order_id)
    {
        $this->order_id = $order_id;
        $this->grand_order_qty = 0;
        $this->grand_total_cutting = 0;
        $this->grand_cutting_print_wip = 0;
        $this->grand_print_sent = 0;
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
        return 'All PO\'s Output Report';
    }


    /**
     * @return Builder
     */
    public function query()
    {
        $fromDate = now()->subDays(150)->startOfDay()->toDateTimeString();
        $toDate = now()->endOfDay()->toDateTimeString();
        $reportData = TotalProductionReport::withoutGlobalScope('factoryId')
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
            orders.style_name, buyers.name as buyer_name, purchase_orders.po_no, purchase_orders.po_quantity, purchase_orders.ex_factory_date')
            ->join('orders', 'orders.id', 'total_production_reports.order_id')
            ->join('purchase_orders', 'purchase_orders.id', 'total_production_reports.purchase_order_id')
            ->join('buyers', 'buyers.id', 'total_production_reports.buyer_id')
            ->where('total_production_reports.factory_id', factoryId())
            ->where('total_production_reports.created_at', '>=', $fromDate)
            ->where('total_production_reports.created_at', '<=', $toDate);

            if ($this->order_id) {
                $reportData = $reportData->where('total_production_reports.order_id', $this->order_id);
            }

            $reportData = $reportData->groupBy('total_production_reports.buyer_id', 'total_production_reports.order_id', 'total_production_reports.purchase_order_id')
                ->orderBy('total_production_reports.purchase_order_id', 'desc');

            return $reportData;
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
        $order = $report->style_name ?? 'Style';
        $po_no = $report->po_no ?? '';
        $po_quantity = $report->po_quantity ?? 0;
        
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
        $this->grand_todays_input += $report->todays_input_sum ?? 0;
        $this->grand_total_input += $report->total_input_sum ?? 0;
        $this->grand_todays_output += $report->todays_sewing_output_sum ?? 0;
        $this->grand_total_output += $report->total_sewing_output_sum ?? 0;
        $this->grand_sewing_rejection += $report->total_sewing_rejection_sum ?? 0;
        $this->grand_total_rejection += ($report->total_cutting_rejection_sum ?? 0) + ($report->total_print_rejection_sum ?? 0) + ($report->total_sewing_rejection_sum ?? 0);
        $this->grand_in_line_wip += ($report->total_input_sum ?? 0) - ($report->total_sewing_output_sum ?? 0);
        return [
            $buyer_name ?? '',
            $order ?? '',
            $po_no ?? '',
            $po_quantity ?? 0,
            $total_cutting ?? 0,
            $cutting_print_wip ?? 0,
            $todays_input ?? 0,
            $total_input ?? 0,
            $todays_sewing_output ?? 0,
            $total_sewing_output ?? 0,
            $total_sewing_rejection ?? 0,
            $total_rejection ?? 0,
            $in_line_wip ?? 0,
            $cut_2_sewing_ratio ?? 0,
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
            'PO Quantity',
            'Cutting Production',
            'WIP In Cutting/Print/Embr.',
            'Today\'s Input to Line',
            'Total Input to Line',
            'Today\'s Output',
            'Total Sewing Output',
            'Sewing Rejection',
            'Total Rejection',
            'In_line WIP',
            'Cut 2 Sewing Ratio',
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
                    $this->grand_order_qty,
                    $this->grand_total_cutting,
                    $this->grand_cutting_print_wip,
                    $this->grand_todays_input,
                    $this->grand_total_input,
                    $this->grand_todays_output,
                    $this->grand_total_output,
                    $this->grand_sewing_rejection,
                    $this->grand_total_rejection,
                    $this->grand_in_line_wip,
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
    }
}