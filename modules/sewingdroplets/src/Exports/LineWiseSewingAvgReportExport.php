<?php

namespace SkylarkSoft\GoRMG\Sewingdroplets\Exports;

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

class LineWiseSewingAvgReportExport implements WithTitle, ShouldAutoSize, FromView, ShouldQueue, WithEvents
{
    use Exportable, CustomExcelHeaderFooter;

    private $result_data;

    public function __construct($data)
    {
        $this->result_data = $data;
    }

    /**
     * @return string
     */
    public function title(): string
    {
        return 'Line Wise Sewing Report';
    }


    /**
     * @return View
     */
    public function view(): View
    {
        $data = $this->result_data;
        return view('sewingdroplets::reports.downloads.excels.line-date-wise-output-avg-report-download', $data);
    }

    /**
     * @return array
     */
    public function registerEvents(): array
    {
        return [
            BeforeExport::class => function (BeforeExport $event) {
                $event->writer->setCreator('Skylark Soft Limited');
            },
            AfterSheet::class => function (AfterSheet $event) {
                $cell_array = range('A', 'G');
                $head_array_number = [1, 2, 3];
                $highestRowNumber = $event->sheet->getDelegate()->getHighestRow();
                $footer_array_number = [$highestRowNumber];
                $this->excelHeaderFooter($event, $cell_array, $head_array_number, $footer_array_number, $footer_exist = 0);
                // Table Body Customize
                $event->sheet->getDelegate()->getStyle($cell_array[0] . ($head_array_number[count($head_array_number) - 1] + 1) . ':' . $cell_array[3] . ($highestRowNumber))->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
                $event->sheet->getDelegate()->getStyle($cell_array[0] . ($head_array_number[count($head_array_number) - 1] + 1) . ':' . $cell_array[3] . ($highestRowNumber))->getAlignment()->setVertical(Alignment::VERTICAL_CENTER);

                $event->sheet->getDelegate()->getStyle($cell_array[4] . ($head_array_number[count($head_array_number) - 1] + 1) . ':' . $cell_array[count($cell_array) - 2] . ($highestRowNumber))->getAlignment()->setHorizontal(Alignment::HORIZONTAL_RIGHT);
                $event->sheet->getDelegate()->getStyle($cell_array[count($cell_array) - 1] . ($head_array_number[count($head_array_number) - 1] + 1) . ':' . $cell_array[count($cell_array) - 1] . ($highestRowNumber))->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

                foreach ($cell_array as $cell) {
                    foreach ($footer_array_number as $head_no) {
                        $event->sheet->styleCells(
                            $cell . $head_no,
                            [
                                'borders' => [
                                    'outline' => [
                                        'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                                        'color' => ['argb' => '00000000'],
                                    ],
                                ]
                            ]
                        );
                    }
                }
            }
        ];
    }
}