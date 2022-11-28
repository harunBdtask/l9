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


class LineWiseCuttingReportExport implements WithTitle, ShouldAutoSize, FromView, ShouldQueue, WithEvents
{
    use Exportable, CustomExcelHeaderFooter;

    private $report_data;

    public function __construct($data)
    {
        $this->report_data = $data;
    }

    /**
     * @return string
     */
    public function title(): string
    {
        return 'Line Wise Cutting Report';
    }

    /**
     * @return View
     */
    public function view(): View
    {
        $data = $this->report_data;
        return view('cuttingdroplets::reports.tables.floor_line_wise_cutting_report_table', $data);
    }

    public function registerEvents(): array
    {
        return [
            BeforeExport::class  => function(BeforeExport $event) {
                $event->writer->setCreator('Skylark Soft Limited');
            },
            AfterSheet::class => function (AfterSheet $event) {
                $cell_array = range('A','L');
                $head_array_number = [1, 2, 3];
                $highestRowNumber = $event->sheet->getDelegate()->getHighestRow();
                $footer_array_number = [$highestRowNumber];
                $this->excelHeaderFooter($event, $cell_array, $head_array_number, $footer_array_number);
                // Table Body Customize                
                $event->sheet->getDelegate()->getStyle($cell_array[0].($head_array_number[count($head_array_number)-1] + 1).':'.$cell_array[5].($highestRowNumber - 1))->getAlignment()->setHorizontal(Alignment::HORIZONTAL_LEFT);
                $event->sheet->getDelegate()->getStyle($cell_array[0].($head_array_number[count($head_array_number)-1] + 1).':'.$cell_array[5].($highestRowNumber - 1))->getAlignment()->setVertical(Alignment::VERTICAL_CENTER);

                $event->sheet->getDelegate()->getStyle($cell_array[5].($head_array_number[count($head_array_number)-1] + 1).':'.$cell_array[count($cell_array) - 1].($highestRowNumber - 1))->getAlignment()->setHorizontal(Alignment::HORIZONTAL_RIGHT);
            }
        ];
    }
}