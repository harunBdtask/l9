<?php

namespace SkylarkSoft\GoRMG\BasicFinance\Exports;

use App\CustomExcelHeaderFooter;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Events\AfterSheet;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\WithTitle;
use PhpOffice\PhpSpreadsheet\Style\Color;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\Exportable;
use Illuminate\Contracts\Queue\ShouldQueue;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class Export implements FromView, WithTitle, WithEvents, ShouldAutoSize, ShouldQueue
{
    use Exportable, CustomExcelHeaderFooter;

    private $viewData, $title, $viewFile, $cellRange, $headerCellVerticalRangeMustNumber;

    public function __construct($viewData, $title, $viewFile)
    {
        $this->viewData = $viewData;
        $this->title = $title;
        $this->viewFile = $viewFile;
//        $this->cellRange = $cellRange;
//        $this->headerCellVerticalRangeMustNumber = $headerCellVerticalRangeMustNumber;
    }

    public function title(): string
    {
        return $this->title;
    }

    public function view(): View
    {
        return view($this->viewFile, $this->viewData);
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {
                $cellRange = 'A1:N1';
                $getHighestRow = $event->sheet->getDelegate()->getHighestRow();
                $event->sheet->getDelegate()->getStyle($cellRange)->getFont()->setBold(true)->setColor(new Color);
                $event->sheet->getDelegate()->getStyle('A1:H' . $getHighestRow)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
            }
        ];
    }
}
