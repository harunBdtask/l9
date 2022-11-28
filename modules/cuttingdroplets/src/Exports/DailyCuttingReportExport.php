<?php

namespace SkylarkSoft\GoRMG\Cuttingdroplets\Exports;


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

class DailyCuttingReportExport implements WithTitle, ShouldAutoSize, FromView, ShouldQueue, WithEvents
{
    use Exportable, CustomExcelHeaderFooter;

    private $cutting_report;
    private $date;

    public function __construct($cutting_report, $date)
    {
        $this->cutting_report = $cutting_report;
        $this->date = $date;
    }

    /**
     * @return string
     */
    public function title(): string
    {
        return date('Y-m-d', strtotime($this->date));
    }


    /**
     * @return View
     */
    public function view(): View
    {
        $cutting_report = $this->cutting_report;
        $date = $this->date;

        return view('cuttingdroplets::reports.tables.daily-cutting-report-table', [
            'cutting_report' => $cutting_report,
            'date' => $date
        ]);
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
	            $head_array_number = [1, 2, 3];
	            $highestRowNumber = $event->sheet->getDelegate()->getHighestRow();
	            $footer_array_number = [$highestRowNumber];
	            $this->excelHeaderFooter($event, $cell_array, $head_array_number, $footer_array_number, $footer_exist = 1);

	            $highestRow = $event->sheet->getDelegate()->getHighestRow();
                $event->sheet->getDelegate()->getStyle('A1:G2')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
                $event->sheet->getDelegate()->getStyle('A1:J3')->getFont()->setBold(true);
                $event->sheet->getDelegate()->getStyle('A1:J1')->getFont()->setSize(14);
                $event->sheet->getDelegate()->getStyle('A3:A'.$highestRow)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
                $event->sheet->getDelegate()->getStyle('B4:C'.($highestRow-1))->getAlignment()->setHorizontal(Alignment::HORIZONTAL_LEFT);
                $event->sheet->getDelegate()->getStyle('F4:J'.($highestRow-1))->getAlignment()->setHorizontal(Alignment::HORIZONTAL_RIGHT);
                $event->sheet->getDelegate()->getStyle('A'.$highestRow.':C'.$highestRow)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
                $event->sheet->getDelegate()->getStyle('A'.$highestRow.':G'.$highestRow)->getFont()->setBold(true);
            }
        ];
    }
}