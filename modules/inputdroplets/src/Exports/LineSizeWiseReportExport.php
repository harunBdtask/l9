<?php

namespace SkylarkSoft\GoRMG\Inputdroplets\Exports;

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

class LineSizeWiseReportExport implements WithTitle, ShouldAutoSize, FromView, ShouldQueue, WithEvents
{
    use Exportable, CustomExcelHeaderFooter;

    private $resultData;

    public function __construct($data)
    {
        $this->resultData = $data;
    }

    /**
     * @return string
     */
    public function title(): string
    {
        return 'Line Size Wise Input Report';
    }


    /**
     * @return View
     */
    public function view(): View
    {
        $data = $this->resultData;
        return view('inputdroplets::reports.downloads.excels.line_size_wise_input_report_download', $data);
    }

    /**
     * @return array
     */
    public function registerEvents(): array
    {
        return [
            BeforeExport::class  => function (BeforeExport $event) {
                $event->writer->setCreator('Skylark Soft Limited');
            },
            AfterSheet::class => function (AfterSheet $event) {
                $cellCount = 8 + count($this->resultData['size_ids']);
                $cell_array = [];
                $initChar = 'A';
                $char = 'A';
                for($i = 0; $i < $cellCount; $i++) {
                    $cell_array[] = $char;
                    $char++;
                    if ($char == $initChar) {
                        $initChar .= 'A';
                        $char = $initChar;
                    }
                }
                $head_array_number = [1, 2, 3, 4];
                $highestRowNumber = $event->sheet->getDelegate()->getHighestRow();
                $footer_array_number = [$highestRowNumber];
                $this->excelHeaderFooter($event, $cell_array, $head_array_number, $footer_array_number);
                // Table Body Customize                
                $event->sheet->getDelegate()->getStyle($cell_array[0] . ($head_array_number[count($head_array_number) - 1] + 1) . ':' . $cell_array[4] . ($highestRowNumber - 1))->getAlignment()->setHorizontal(Alignment::HORIZONTAL_LEFT);
                $event->sheet->getDelegate()->getStyle($cell_array[0] . ($head_array_number[count($head_array_number) - 1] + 1) . ':' . $cell_array[4] . ($highestRowNumber - 1))->getAlignment()->setVertical(Alignment::VERTICAL_CENTER);

                $event->sheet->getDelegate()->getStyle($cell_array[6] . ($head_array_number[count($head_array_number) - 1] + 1) . ':' . $cell_array[5] . ($highestRowNumber - 1))->getAlignment()->setHorizontal(Alignment::HORIZONTAL_RIGHT);
            }
        ];
    }
}
