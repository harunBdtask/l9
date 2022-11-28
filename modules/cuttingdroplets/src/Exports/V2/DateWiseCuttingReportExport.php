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

class DateWiseCuttingReportExport implements WithTitle, ShouldAutoSize, FromView, ShouldQueue, WithEvents
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
        return date('Y-m-d', strtotime($this->data['date']));
    }


    /**
     * @return View
     */
    public function view(): View
    {
        return view('cuttingdroplets::reports.downloads.excels.v2.date_wise_cutting_report', $this->data);
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
	            $cell_array = range('A', 'P');
	            $head_array_number = [1, 2, 3, 4];
	            $highestRowNumber = $event->sheet->getDelegate()->getHighestRow();
	            $footer_array_number = [$highestRowNumber];
	            $this->excelHeaderFooter($event, $cell_array, $head_array_number, $footer_array_number, $footer_exist = 1);

	            $highestRow = $event->sheet->getDelegate()->getHighestRow();
                $event->sheet->getDelegate()->getStyle('A1:P4')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
                $event->sheet->getDelegate()->getStyle('A1:P4')->getFont()->setBold(true);
                $event->sheet->getDelegate()->getStyle('A1:P4')->getFont()->setSize(14);
                $event->sheet->getDelegate()->getStyle('A4:K'.$highestRow)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
                $event->sheet->getDelegate()->getStyle('L4:P'.($highestRow-1))->getAlignment()->setHorizontal(Alignment::HORIZONTAL_RIGHT);
                $event->sheet->getDelegate()->getStyle('A'.$highestRow.':K'.$highestRow)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
                $event->sheet->getDelegate()->getStyle('L'.$highestRow.':P'.$highestRow)->getFont()->setBold(true);
            }
        ];
    }
}