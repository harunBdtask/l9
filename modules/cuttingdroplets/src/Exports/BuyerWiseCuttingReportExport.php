<?php

namespace SkylarkSoft\GoRMG\Cuttingdroplets\Exports;


use App\CustomExcelHeaderFooter;
use DB;
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
use SkylarkSoft\GoRMG\SystemSettings\Models\Buyer;


class BuyerWiseCuttingReportExport implements WithTitle, ShouldAutoSize, WithMapping, WithHeadings, FromQuery, WithEvents, ShouldQueue, WithCustomChunkSize
{
    use Exportable, CustomExcelHeaderFooter;

    private $buyer_id, $buyer_name, $from_date, $to_date;
    private $grand_po_qty;
    private $grand_todays_cutting;
    private $grand_todays_cutting_rejection;
    private $grand_todays_ok_cutting;
    private $grand_total_cutting;
    private $grand_total_cutting_rejection;
    private $grand_total_ok_cutting;
    private $grand_left_extra_qty;

    public function __construct($request)
    {
        $this->buyer_id = $request['buyer_id'];
        $this->from_date = $request['from_date'];
        $this->to_date = $request['to_date'];
        $this->buyer_name = Buyer::query()->findOrFail($this->buyer_id)->name;
        $this->grand_po_qty = 0;
        $this->grand_todays_cutting = 0;
        $this->grand_todays_cutting_rejection = 0;
        $this->grand_todays_ok_cutting = 0;
        $this->grand_total_cutting = 0;
        $this->grand_total_cutting_rejection = 0;
        $this->grand_total_ok_cutting = 0;
        $this->grand_left_extra_qty = 0;
    }

    /**
     * @return string
     */
    public function title(): string
    {
        return 'Buyer Wise Cutting Production';
    }


    /**
     * @return Builder
     */
    public function query()
    {
        $buyer_id = $this->buyer_id;
        $from_date = $this->from_date;
        $to_date = $this->to_date;

        return DB::table('total_production_reports')
            ->selectRaw('total_production_reports.order_id, total_production_reports.purchase_order_id, 
            sum(total_production_reports.todays_cutting) as todays_cutting, 
            sum(total_production_reports.total_cutting) as total_cutting, 
            sum(total_production_reports.todays_cutting_rejection) as todays_cutting_rejection, 
            sum(total_production_reports.total_cutting_rejection) as total_cutting_rejection,            
            orders.style_name, orders.repeat_no, purchase_orders.po_no, purchase_orders.po_quantity')
            ->where('total_production_reports.buyer_id', $buyer_id)
            ->where('total_production_reports.factory_id', factoryId())
	        ->when(($from_date == '' || $to_date == ''), function($query) {
		        $date = now()->subDays(186)->toDateString();
		        $query->whereDate('total_production_reports.created_at', '>=', $date);
	        })
	        ->when(($from_date != '' && $to_date != ''), function($query) use ($from_date, $to_date) {
		        $query->whereDate('total_production_reports.created_at', '>=', $from_date)
			        ->whereDate('total_production_reports.created_at', '<=', $to_date);
	        })
	        ->join('orders', 'orders.id', 'total_production_reports.order_id')
	        ->join('purchase_orders', 'purchase_orders.id', 'total_production_reports.purchase_order_id')
	        ->groupBy('total_production_reports.buyer_id', 'total_production_reports.order_id', 'total_production_reports.purchase_order_id')
            ->orderBy('total_production_reports.order_id', 'desc')
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
        $buyer_name = $this->buyer_name ?? 'Buyer';
        $order = $report->style_name ?? '';
        $purchase_order_no = $report->po_no ?? '';
        $po_quantity = $report->po_quantity ?? 0;

	    $todaysCutting = $report->todays_cutting;
	    $todaysCuttingRejection = $report->todays_cutting_rejection;
	    $todaysOkCutting = $todaysCutting - $todaysCuttingRejection;
	    $totalCutting = $report->total_cutting;
	    $totalCuttingRejection = $report->total_cutting_rejection;
	    $totalOkCutting = $totalCutting - $totalCuttingRejection;

        $xtra = ($po_quantity > 0) ? ((($totalOkCutting - $po_quantity) * 100) / $po_quantity) : 0;
        $xtra = $xtra > 0 ? $xtra : 0;
        $leftQty = $po_quantity - $totalOkCutting;

        $this->grand_po_qty += $po_quantity ?? 0;
        $this->grand_todays_cutting += $todaysCutting ?? 0;
        $this->grand_todays_cutting_rejection += $todaysCuttingRejection ?? 0;
        $this->grand_todays_ok_cutting += $todaysOkCutting ?? 0;
        $this->grand_total_cutting += $totalCutting ?? 0;
        $this->grand_total_cutting_rejection += $totalCuttingRejection ?? 0;
        $this->grand_total_ok_cutting += $totalOkCutting ?? 0;
        $this->grand_left_extra_qty += $leftQty;
        return [
            $buyer_name ?? '',
            $order ?? '',
            $purchase_order_no ?? '',
            $po_quantity ?? 0,
            $todaysCutting ?? 0,
            $todaysCuttingRejection ?? 0,
            $todaysOkCutting ?? 0,
            $totalCutting ?? 0,
            $totalCuttingRejection ?? 0,
            $totalOkCutting ?? 0,
            $leftQty ?? '',
            round($xtra, 2) . '%'
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
            'Purchase Order',
            'PO Qty',
            'Today\'s Cutting',
            'Today\'s Cutting Rejection',
            'Today\'s OK Cutting',
            'Total Cutting',
            'Total Cutting Rejection',
            'Total OK Cutting',
            'Left/Extra Qty',
            'Extra Cutting (%)',
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
		                'Buyer Wise Cutting Report'
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
                    '',
                    $this->grand_po_qty,
                    $this->grand_todays_cutting,
                    $this->grand_todays_cutting_rejection,
                    $this->grand_todays_ok_cutting,
                    $this->grand_total_cutting,
                    $this->grand_total_cutting_rejection,
                    $this->grand_total_ok_cutting,
                    $this->grand_left_extra_qty,
                    ''
                ]);

	            $cell_array = range('A', 'M');
	            $head_array_number = [1, 2, 3];
	            $highestRowNumber = $event->sheet->getDelegate()->getHighestRow();
	            $footer_array_number = [$highestRowNumber];
	            $this->excelHeaderFooter($event, $cell_array, $head_array_number, $footer_array_number, $footer_exist = 1);

	            // Custom Style
	            $event->sheet->getDelegate()->getStyle($cell_array[0] . $head_array_number[0] . ':' . $cell_array[4] . ($footer_array_number[count($footer_array_number) - 1] - 1))->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
	            $event->sheet->getDelegate()->getStyle($cell_array[0] . $head_array_number[0] . ':' . $cell_array[4] . ($footer_array_number[count($footer_array_number) - 1] - 1))->getAlignment()->setVertical(Alignment::VERTICAL_CENTER);
	            $event->sheet->getDelegate()->getStyle($cell_array[5] . $head_array_number[0] . ':' . $cell_array[8] . ($footer_array_number[count($footer_array_number) - 1] - 1))->getAlignment()->setHorizontal(Alignment::HORIZONTAL_RIGHT);
	            $event->sheet->getDelegate()->getStyle($cell_array[5] . $head_array_number[0] . ':' . $cell_array[8] . ($footer_array_number[count($footer_array_number) - 1] - 1))->getAlignment()->setVertical(Alignment::VERTICAL_CENTER);
	            $event->sheet->mergeCells('A' . $head_array_number[0] . ':I' . $head_array_number[0]);
	            $event->sheet->mergeCells('A' . $head_array_number[1] . ':I' . $head_array_number[1]);
	            $event->sheet->mergeCells('A' . $highestRowNumber . ':D' . $highestRowNumber);
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