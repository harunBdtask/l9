<?php

namespace SkylarkSoft\GoRMG\Cuttingdroplets\Exports;


use App\CustomExcelHeaderFooter;
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
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use SkylarkSoft\GoRMG\Cuttingdroplets\Models\TotalProductionReport;


class ExcessCuttingReportExport implements WithTitle, ShouldAutoSize, WithMapping, WithHeadings, FromQuery, WithEvents, ShouldQueue, WithCustomChunkSize
{
    use Exportable, CustomExcelHeaderFooter;

    private $request_data;
    private $grand_order_qty;
    private $grand_todays_cutting;
    private $grand_total_cutting;
    private $grand_extra_qty;

    public function __construct($request_data)
    {
        $this->request_data = $request_data;
        $this->grand_order_qty = 0;
        $this->grand_todays_cutting = 0;
        $this->grand_total_cutting = 0;
        $this->grand_extra_qty = 0;
    }

    /**
     * @return string
     */
    public function title(): string
    {
        return 'Excess Cutting Report';
    }

    /**
     * @return Builder
     */
    public function query()
    {
        $buyer_id = $this->request_data['buyer_id'] ?? '';
        $order_id = $this->request_data['order_id'] ?? '';
        $from_date = $this->request_data['from_date'] ?? '';
        $to_date = $this->request_data['to_date'] ?? '';
        return TotalProductionReport::query()
	        ->withoutGlobalScope('factoryId')
	        ->selectRaw(
		        'total_production_reports.buyer_id,
				total_production_reports.order_id,
				total_production_reports.purchase_order_id,
				sum(total_production_reports.todays_cutting) as todays_cutting,
				sum(total_production_reports.todays_cutting_rejection) as todays_cutting_rejection,
				sum(total_production_reports.total_cutting) as total_cutting,
				sum(total_production_reports.total_cutting_rejection) as total_cutting_rejection
				'
	        )
	        ->when(($buyer_id == '' && $order_id == '' && ($from_date == '' || $to_date == '')), function($query) {
		        $date = now()->subDays(186)->toDateString();
		        $query->whereDate('total_production_reports.created_at', '>=', $date);
	        })
	        ->when(($from_date != '' && $to_date != ''), function($query) use ($from_date, $to_date) {
		        $query->whereDate('total_production_reports.created_at', '>=', $from_date)
			        ->whereDate('total_production_reports.created_at', '<=', $to_date);
	        })
	        ->where('total_production_reports.factory_id', factoryId())
	        ->when($buyer_id != '', function ($query) use ($buyer_id) {
		        return $query->where('total_production_reports.buyer_id', $buyer_id);
	        })
	        ->when($order_id != '', function ($query) use ($order_id) {
		        return $query->where('total_production_reports.order_id', $order_id);
	        })
	        ->join('purchase_orders', 'purchase_orders.id', 'total_production_reports.purchase_order_id')
            ->when(($buyer_id == '' || $order_id == ''), function ($query) {
                $query->whereRaw('(total_production_reports.total_cutting - total_production_reports.total_cutting_rejection) >= purchase_orders.po_pc_quantity');
            })
	        ->with(['buyer:id,name', 'order:id,style_name', 'purchaseOrder:id,po_no,po_quantity,po_pc_quantity'])
	        ->groupBy('total_production_reports.buyer_id', 'total_production_reports.order_id', 'total_production_reports.purchase_order_id')
	        ->orderBy('total_production_reports.order_id');
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
        $booking_no = $report->order->style_name ?? 'Style';
        $po_no = $report->purchaseOrder->po_no ?? '';
        $order_qty = $report->purchaseOrder->po_pc_quantity > 0 ? $report->purchaseOrder->po_pc_quantity : $report->purchaseOrder->po_quantity;
        $todaysCuttingRejection = $report->todays_cutting_rejection ?? 0;
        $todaysCutting = ($report->todays_cutting ?? 0) - $todaysCuttingRejection;
        $totalCuttingRejection = $report->total_cutting_rejection ?? 0;
        $totalCutting = ($report->total_cutting ?? 0) - $totalCuttingRejection;
        if ($order_qty > $totalCutting) {
            return [];
        }
        $extra_cutting = $totalCutting - $order_qty;
        $extra_cutting_percent = ($order_qty > 0) ? ((($totalCutting - $order_qty) * 100) / $order_qty) : 0;

        $this->grand_order_qty += $order_qty ?? 0;
        $this->grand_todays_cutting += $todaysCutting;
        $this->grand_total_cutting += $totalCutting;
        $this->grand_extra_qty += $extra_cutting;
        return [
            $buyer_name ?? '',
            $booking_no ?? '',
            $po_no ?? '',
            $order_qty ?? 0,
            $todaysCutting ?? 0,
            $totalCutting ?? 0,
            $extra_cutting ?? '',
            number_format($extra_cutting_percent,2) . '%'
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
            'PO Qty',
            'Today\'s Cutting',
            'Total Cutting',
            'Extra Qty',
            'Extra Cutting(%)',
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
	                [
		                'Excess Cutting Report'
	                ],
	                [
		                sessionFactoryName(),
	                ]
                ]);
            },
            AfterSheet::class => function (AfterSheet $event) {
                $event->sheet->append([
                    'Total',
                    '',
                    '',
                    $this->grand_order_qty,
                    $this->grand_todays_cutting,
                    $this->grand_total_cutting,
                    $this->grand_extra_qty,
                    ''
                ]);

	            $cell_array = range('A', 'H');
	            $head_array_number = [1, 2, 3];
	            $highestRowNumber = $event->sheet->getDelegate()->getHighestRow();
	            $footer_array_number = [$highestRowNumber];
	            $this->excelHeaderFooter($event, $cell_array, $head_array_number, $footer_array_number, $footer_exist = 1);

	            // Custom Style
	            $event->sheet->getDelegate()->getStyle($cell_array[0] . $head_array_number[0] . ':' . $cell_array[3] . ($footer_array_number[count($footer_array_number) - 1] - 1))->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
	            $event->sheet->getDelegate()->getStyle($cell_array[0] . $head_array_number[0] . ':' . $cell_array[3] . ($footer_array_number[count($footer_array_number) - 1] - 1))->getAlignment()->setVertical(Alignment::VERTICAL_CENTER);
	            $event->sheet->getDelegate()->getStyle($cell_array[3] . $head_array_number[0] . ':' . $cell_array[7] . ($footer_array_number[count($footer_array_number) - 1] - 1))->getAlignment()->setHorizontal(Alignment::HORIZONTAL_RIGHT);
	            $event->sheet->getDelegate()->getStyle($cell_array[3] . $head_array_number[0] . ':' . $cell_array[7] . ($footer_array_number[count($footer_array_number) - 1] - 1))->getAlignment()->setVertical(Alignment::VERTICAL_CENTER);
	            $event->sheet->mergeCells('A' . $head_array_number[0] . ':H' . $head_array_number[0]);
	            $event->sheet->mergeCells('A' . $head_array_number[1] . ':H' . $head_array_number[1]);
	            $event->sheet->mergeCells('A' . $highestRowNumber . ':C' . $highestRowNumber);
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