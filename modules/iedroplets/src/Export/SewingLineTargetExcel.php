<?php

namespace SkylarkSoft\GoRMG\Iedroplets\Export;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use SkylarkSoft\GoRMG\Iedroplets\PackageConst;

class SewingLineTargetExcel implements WithTitle, ShouldAutoSize, FromView, ShouldQueue, withEvents
{
    use Exportable;

    private $resultData;
    private $grandTotal;
    private $middleSection;

    public function __construct($data)
    {
        $this->resultData = $data;
        $this->grandTotal = $this->resultData['total_number_of_rows']['upper_section'];
        $this->middleSection = $this->resultData['total_number_of_rows']['middle_section'];
    }

    /**
     * @return string
     */
    public function title(): string
    {
        return 'Sewing Line Target Report';
    }

    /**
     * @return View
     */
    public function view(): View
    {
        $data = $this->resultData;
        return view(PackageConst::PACKAGE_NAME . '::reports.downloads.excels.sewing-line-target-download', $data);
    }

    /**
     * @return array
     */
    public function registerEvents(): array
    {
        return [AfterSheet::class => function (AfterSheet $event) {
            $cellRange = 'A3:Q3'; //
            $firstHighestRowNumber = $this->grandTotal + 2;
            $middleSection = $this->middleSection;
            $secondHighestRowNumber = $firstHighestRowNumber + 3 + $middleSection;
            $event->sheet->getDelegate()->getStyle('A1:Q3')->getFont()->setBold(true);
            $event->sheet->getDelegate()->getStyle('A1:Q1')->getFont()->setSize(18);
            $event->sheet->getDelegate()->getStyle('A2:Q2')->getFont()->setSize(16);
            $event->sheet->getDelegate()->getStyle($cellRange)->getFont()->setBold(true);

            $event->sheet->getDelegate()->getStyle('A1:A' . $firstHighestRowNumber)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
            $event->sheet->getDelegate()->getStyle('A' . $firstHighestRowNumber . ':Q' . $firstHighestRowNumber)->getFont()->setBold(true);
            $event->sheet->getDelegate()->getStyle($cellRange)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
            $event->sheet->getDelegate()->getStyle('B1:B' . ($firstHighestRowNumber))->getAlignment()->setHorizontal(Alignment::HORIZONTAL_LEFT);
            $event->sheet->getDelegate()->getStyle('C1:C' . ($firstHighestRowNumber))->getAlignment()->setHorizontal(Alignment::HORIZONTAL_LEFT);
            $event->sheet->getDelegate()->getStyle('D1:D' . ($firstHighestRowNumber))->getAlignment()->setHorizontal(Alignment::HORIZONTAL_LEFT);
            $event->sheet->getDelegate()->getStyle('E1:E' . ($firstHighestRowNumber))->getAlignment()->setHorizontal(Alignment::HORIZONTAL_LEFT);
            $event->sheet->getDelegate()->getStyle('F1:F' . ($firstHighestRowNumber))->getAlignment()->setHorizontal(Alignment::HORIZONTAL_LEFT);
            $event->sheet->getDelegate()->getStyle('G2:O' . ($firstHighestRowNumber))->getAlignment()->setHorizontal(Alignment::HORIZONTAL_RIGHT);
            $event->sheet->getDelegate()->getStyle('P2:Q' . ($firstHighestRowNumber - 2))->getAlignment()->setHorizontal(Alignment::HORIZONTAL_LEFT);
            $event->sheet->getDelegate()->getStyle('A' . ($firstHighestRowNumber + 2))->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
            $event->sheet->getDelegate()->getStyle('A' . ($firstHighestRowNumber + 2))->getFont()->setBold(true);
            $event->sheet->getDelegate()->getStyle('A' . ($firstHighestRowNumber + 3) . ':E' . ($firstHighestRowNumber + 3))->getAlignment()->setVertical(Alignment::VERTICAL_CENTER)->setHorizontal(Alignment::HORIZONTAL_CENTER);
            $event->sheet->getDelegate()->getStyle('A' . ($firstHighestRowNumber + 3) . ':E' . ($firstHighestRowNumber + 3))->getFont()->setBold(true);
            $event->sheet->getDelegate()->getStyle('A' . ($firstHighestRowNumber + 4) . ':A' . ($firstHighestRowNumber + 4 + $middleSection))->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
            $event->sheet->getDelegate()->getStyle('A' . ($firstHighestRowNumber + 4) . ':A' . ($firstHighestRowNumber + 4 + $middleSection))->getFont()->setBold(true);
            $event->sheet->getDelegate()->getStyle('B' . ($firstHighestRowNumber + 4) . ':B' . ($firstHighestRowNumber + 4 + $middleSection))->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
            $event->sheet->getDelegate()->getStyle('D' . ($firstHighestRowNumber + 4) . ':D' . ($firstHighestRowNumber + 4 + $middleSection))->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
            $event->sheet->getDelegate()->getStyle('D' . ($firstHighestRowNumber + 4) . ':D' . ($firstHighestRowNumber + 4 + $middleSection))->getFont()->setBold(true);
            $event->sheet->getDelegate()->getStyle('E' . ($firstHighestRowNumber + 4) . ':E' . ($firstHighestRowNumber + 4 + $middleSection))->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
            $event->sheet->getDelegate()->getStyle('G' . ($firstHighestRowNumber + 3))->getFont()->setBold(true);
            $event->sheet->getDelegate()->getStyle('G' . ($firstHighestRowNumber + 3))->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
            $event->sheet->getDelegate()->getStyle('G' . ($firstHighestRowNumber + 4) . ':H' . ($firstHighestRowNumber + 4))->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
            $event->sheet->getDelegate()->getStyle('G' . ($firstHighestRowNumber + 4) . ':H' . ($firstHighestRowNumber + 4))->getFont()->setBold(true);
            $event->sheet->getDelegate()->getStyle('G' . ($firstHighestRowNumber + 4) . ':G' . ($firstHighestRowNumber + 10))->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
            $event->sheet->getDelegate()->getStyle('H' . ($firstHighestRowNumber + 4) . ':H' . ($firstHighestRowNumber + 10))->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
            $event->sheet->getDelegate()->getStyle('G' . ($firstHighestRowNumber + 10))->getFont()->setBold(true);
            $event->sheet->getDelegate()->getStyle('A' . ($firstHighestRowNumber + 5 + $middleSection + 1))->getFont()->setBold(true);
            $event->sheet->getDelegate()->getStyle('A' . ($firstHighestRowNumber + 5 + $middleSection + 1))->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
            $event->sheet->getDelegate()->getStyle('A' . ($firstHighestRowNumber + 5 + $middleSection + 2) . ':G' . ($firstHighestRowNumber + 5 + $middleSection + 2))->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
            $event->sheet->getDelegate()->getStyle('A' . ($firstHighestRowNumber + 5 + $middleSection + 2) . ':G' . ($firstHighestRowNumber + 5 + $middleSection + 2))->getFont()->setBold(true);
            $event->sheet->getDelegate()->getStyle('A' . ($firstHighestRowNumber + 5 + $middleSection + 2) . ':H' . ($firstHighestRowNumber + 5 + $middleSection + 2))->getFont()->setBold(true);
            $event->sheet->getDelegate()->getStyle('A' . ($firstHighestRowNumber + 5 + $middleSection + 3) . ':H' . ($firstHighestRowNumber + 5 + $middleSection + 3))->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
            $event->sheet->getDelegate()->getStyle('A' . ($firstHighestRowNumber + 5 + $middleSection + 3) . ':H' . ($firstHighestRowNumber + 5 + $middleSection + 3))->getFont()->setBold(true);
            $event->sheet->getDelegate()->getStyle('A' . ($firstHighestRowNumber + 5 + $middleSection + 3) . ':A' . ($firstHighestRowNumber + 5 + $middleSection + 6))->getFont()->setBold(true);
            $event->sheet->getDelegate()->getStyle('A' . ($firstHighestRowNumber + 5 + $middleSection + 3) . ':A' . ($firstHighestRowNumber + 5 + $middleSection + 6))->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
            $event->sheet->getDelegate()->getStyle('B' . ($firstHighestRowNumber + 5 + $middleSection + 4) . ':D' . ($firstHighestRowNumber + 5 + $middleSection + 6))->getAlignment()->setHorizontal(Alignment::HORIZONTAL_RIGHT);
            $event->sheet->getDelegate()->getStyle('G' . ($firstHighestRowNumber + 5 + $middleSection + 4) . ':H' . ($firstHighestRowNumber + 5 + $middleSection + 6 + 6))->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
            $event->sheet->getDelegate()->getStyle('G' . ($firstHighestRowNumber + 5 + $middleSection + 9))->getFont()->setBold(true);
            $event->sheet->getDelegate()->getStyle($cellRange)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
        },
        ];
    }
}
