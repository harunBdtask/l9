<?php


namespace SkylarkSoft\GoRMG\GeneralStore\Exports;


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
use SkylarkSoft\GoRMG\Skeleton\Traits\CustomExcelHeaderFooter;

class InventoryExcelReport implements WithTitle, WithEvents, ShouldAutoSize, FromView, ShouldQueue
{
    use Exportable, CustomExcelHeaderFooter;

    public $viewData, $excelFile, $title, $headerCellHorizontalRangeMustLetter, $headerCellVerticalRangeMustNumber;

    public function __construct($viewData, $excelFile, $title, $headerCellHorizontalRangeMustLetter = null, $headerCellVerticalRangeMustNumber = null)
    {
        $this->viewData = $viewData;
        $this->excelFile = $excelFile;
        $this->title = $title;
        $this->headerCellHorizontalRangeMustLetter = $headerCellHorizontalRangeMustLetter;
        $this->headerCellVerticalRangeMustNumber = $headerCellVerticalRangeMustNumber;
    }
    public function title(): string
    {
        return $this->title;
    }
    public function view(): View
    {
        return view($this->excelFile, $this->viewData);
    }

    public function registerEvents(): array
    {
        return [
            BeforeExport::class => function (BeforeExport $event) {
                $event->writer->setCreator('Skylark Soft Limited');
            },
            AfterSheet::class => function (AfterSheet $event) {
                $cell_array = $this->headerCellHorizontalRangeMustLetter;
                $head_array_number = $this->headerCellVerticalRangeMustNumber;
                $highestRowNumber = $event->sheet->getDelegate()->getHighestRow();
                $footer_array_number = [$highestRowNumber];
                $this->excelHeaderFooter($event, $cell_array, $head_array_number, $footer_array_number);


                $event->sheet->getDelegate()->getStyle($cell_array[0] . ($head_array_number[count($head_array_number) - 1] + 1) . ':' . $cell_array[1] . ($highestRowNumber))->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
                $event->sheet->getDelegate()->getStyle($cell_array[1] . ($head_array_number[count($head_array_number) - 1] + 1) . ':' . $cell_array[1] . ($highestRowNumber))->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
                $event->sheet->getDelegate()->getStyle($cell_array[0] . ($head_array_number[count($head_array_number) - 1] + 1) . ':' . $cell_array[1] . ($highestRowNumber))->getAlignment()->setVertical(Alignment::VERTICAL_CENTER);
                $event->sheet->getDelegate()->getStyle($cell_array[7] . ($head_array_number[count($head_array_number) - 1] + 1) . ':' . $cell_array[count($cell_array) - 1] . ($highestRowNumber))->getAlignment()->setHorizontal(Alignment::HORIZONTAL_RIGHT);
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
