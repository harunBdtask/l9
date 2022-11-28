<?php

namespace SkylarkSoft\GoRMG\Finishingdroplets\Exports;

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

class AllOrdersPolyReportExport implements WithTitle, ShouldAutoSize, WithMapping, WithHeadings, FromQuery, WithEvents, ShouldQueue, WithCustomChunkSize
{
    use Exportable;

    private $grand_order_qty;
    private $grand_total_cutting;
    private $grand_todays_input;
    private $grand_total_input;
    private $grand_todays_output;
    private $grand_total_output;
    private $grand_todays_poly;
    private $grand_total_poly;
    private $grand_todays_cartoon;
    private $grand_total_cartoon;
    private $grand_todays_pcs;
    private $grand_total_pcs;
    private $grand_total_rejection;

    public function __construct()
    {
        $this->grand_order_qty = 0;
        $this->grand_total_cutting = 0;
        $this->grand_todays_input = 0;
        $this->grand_total_input = 0;
        $this->grand_todays_output = 0;
        $this->grand_total_output = 0;
        $this->grand_todays_poly = 0;
        $this->grand_total_poly = 0;
        $this->grand_todays_cartoon = 0;
        $this->grand_total_cartoon = 0;
        $this->grand_todays_pcs = 0;
        $this->grand_total_pcs = 0;
        $this->grand_total_rejection = 0;
    }

    /**
     * @return string
     */
    public function title(): string
    {
        return 'All PO\'s Poly Report';
    }


    /**
     * @return Builder
     */
    public function query()
    {
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
            sum(total_production_reports.todays_poly) as todays_poly_sum, 
            sum(total_production_reports.total_poly) as total_poly_sum, 
            sum(total_production_reports.todays_cartoon) as todays_cartoon_sum, 
            sum(total_production_reports.total_cartoon) as total_cartoon_sum, 
            sum(total_production_reports.todays_pcs) as todays_pcs_sum, 
            sum(total_production_reports.total_pcs) as total_pcs_sum,
            orders.order_style_no, orders.repeat_no, orders.order_confirmation_date, buyers.name as buyer_name, purchase_orders.po_no, purchase_orders.po_quantity, purchase_orders.ex_factory_date')
            ->join('orders', 'orders.id', 'total_production_reports.order_id')
            ->join('purchase_orders', 'purchase_orders.id', 'total_production_reports.purchase_order_id')
            ->join('buyers', 'buyers.id', 'total_production_reports.buyer_id')
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

        $order = $report->order_style_no ?? 'Style';
        if ($report->order_style_no && $report->repeat_no) {
            $order .= '(' . $report->repeat_no . ')';
        }
        $po_no = $report->po_no ?? '';
        $po_quantity = $report->po_quantity ?? 0;
        
        $todaysCutting = $report->todays_cutting_sum ?? 0;
        $totalCutting = $report->total_cutting_sum ?? 0;
        
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
        $todays_poly = $report->todays_poly_sum ?? 0;
        $total_poly = $report->total_poly_sum ?? 0;
        $todays_cartoon = $report->todays_cartoon_sum ?? 0;
        $total_cartoon = $report->total_cartoon_sum ?? 0;
        $todays_pcs = $report->todays_pcs_sum ?? 0;
        $total_pcs = $report->total_pcs_sum ?? 0;

        $total_rejection = ($report->total_cutting_rejection_sum ?? 0) + ($report->total_print_rejection_sum ?? 0) + ($report->total_sewing_rejection_sum ?? 0);

        $poly_percentage = ($report->todays_poly_sum > 0 && $report->total_cutting_sum > 0) ? number_format(($report->todays_poly_sum / $report->total_cutting_sum) * 100, 2) : 0;

        $this->grand_order_qty += $po_quantity ?? 0;
        $this->grand_total_cutting += $report->total_cutting_sum ?? 0;
        $this->grand_todays_input += $report->todays_input_sum ?? 0;
        $this->grand_total_input += $report->total_input_sum ?? 0;
        $this->grand_todays_output += $report->todays_sewing_output_sum ?? 0;
        $this->grand_total_output += $report->total_sewing_output_sum ?? 0;

        $this->grand_todays_poly += $report->todays_poly_sum ?? 0;
        $this->grand_total_poly += $report->total_poly_sum ?? 0;
        $this->grand_todays_cartoon += $report->todays_cartoon_sum ?? 0;
        $this->grand_total_cartoon += $report->total_cartoon_sum ?? 0;
        $this->grand_todays_pcs += $report->todays_pcs_sum ?? 0;
        $this->grand_total_pcs += $report->total_pcs_sum ?? 0;

        $this->grand_total_rejection += ($report->total_cutting_rejection_sum ?? 0) + ($report->total_print_rejection_sum ?? 0) + ($report->total_sewing_rejection_sum ?? 0);
        return [
            $buyer_name ?? '',
            $order ?? '',
            $po_no ?? '',
            $po_quantity ?? 0,
            $total_cutting ?? 0,
            $todays_input ?? 0,
            $total_input ?? 0,
            $todays_sewing_output ?? 0,
            $total_sewing_output ?? 0,
            $todays_poly ?? 0,
            $total_poly ?? 0,
            $poly_percentage ?? '',
            $todays_cartoon ?? 0,
            $total_cartoon ?? 0,
            $todays_pcs ?? 0,
            $total_pcs ?? 0,
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
            'PO Qty',
            'Cut. Production',
            'Today\'s Input',
            'Total Input',
            'Today\'s Output',
            'Total Output',
            'Today\'s Poly',
            'Total Poly',
            '%Poly',
            'Today\'s Cartoon',
            'Total Cartoon',
            'Today Pcs',
            'Total Pcs',
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
                    $this->grand_order_qty,
                    $this->grand_total_cutting,
                    $this->grand_todays_input,
                    $this->grand_total_input,
                    $this->grand_todays_output,
                    $this->grand_total_output,
                    $this->grand_todays_poly,
                    $this->grand_total_poly,
                    '',
                    $this->grand_todays_cartoon,
                    $this->grand_total_cartoon,
                    $this->grand_todays_pcs,
                    $this->grand_total_pcs,
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