<?php

namespace SkylarkSoft\GoRMG\Cuttingdroplets\Exports\V2;

use App\CustomExcelHeaderFooter;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Events\AfterSheet;
use Maatwebsite\Excel\Events\BeforeExport;
use PhpOffice\PhpSpreadsheet\Style\Alignment;

class OrderWiseCuttingReportExport implements WithTitle, ShouldAutoSize, FromView, ShouldQueue, WithEvents
{
    use Exportable, CustomExcelHeaderFooter;

    private $data;

    public function __construct($data)
    {
        $this->data = $data;
    }

    /**
     * @return string
     */
    public function title(): string
    {
        return 'Order Wise Cutting Report';
    }


    /**
     * @return View
     */
    public function view(): View
    {
        return view('cuttingdroplets::reports.tables.v2.order_wise_cutting_report_table', $this->data);
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
            AfterSheet::class => function( AfterSheet $event ){
	            $cell_array = range('A', 'J');
	            $head_array_number = [1, 2, 3, 4];
	            $highestRowNumber = $event->sheet->getDelegate()->getHighestRow();
	            $footer_array_number = [$highestRowNumber];
	            $this->excelHeaderFooter($event, $cell_array, $head_array_number, $footer_array_number, $footer_exist = 1);

	            $highestRow = $event->sheet->getDelegate()->getHighestRow();
                $event->sheet->getDelegate()->getStyle('A1:J4')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
                $event->sheet->getDelegate()->getStyle('A1:J4')->getFont()->setBold(true);
                $event->sheet->getDelegate()->getStyle('A1:J4')->getFont()->setSize(14);
                $event->sheet->getDelegate()->getStyle('A4:E'.$highestRow)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
                $event->sheet->getDelegate()->getStyle('F4:J'.($highestRow-1))->getAlignment()->setHorizontal(Alignment::HORIZONTAL_RIGHT);
                $event->sheet->getDelegate()->getStyle('A'.$highestRow.':E'.$highestRow)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
                $event->sheet->getDelegate()->getStyle('A'.$highestRow.':J'.$highestRow)->getFont()->setBold(true);
            }
        ];
    }
}