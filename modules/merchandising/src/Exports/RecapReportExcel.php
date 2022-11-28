<?php
/**
 * Created by PhpStorm.
 * User: siam
 * Date: 3/16/19
 * Time: 9:42 AM
 */

namespace SkylarkSoft\GoRMG\Merchandising\Exports;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;

class RecapReportExcel implements WithTitle, ShouldAutoSize, FromView, ShouldQueue, withEvents
{
    use Exportable;
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
        return 'Recap Report';
    }

    /**
     * @return View
     */
    public function view(): View
    {
        $data = $this->resultData;

        return view('merchandising::recap.recap_report_excel_new', $data);
    }

    /**
     * @return array
     */
    public function registerEvents(): array
    {
        return [ AfterSheet::class => function (AfterSheet $event) {
            $cellRange = 'A5:W5'; //
            $highestRowNumber = $event->sheet->getDelegate()->getHighestRow();
            $event->sheet->getDelegate()->getStyle($cellRange)->getFont()->setBold(true);
            $event->sheet->getDelegate()->getStyle('A1:A2')->getFont()->setBold(true);
            $event->sheet->getDelegate()->getStyle('A1')->getFont()->setSize(14);
            $event->sheet->getDelegate()->getStyle('A2')->getFont()->setSize(12);
            $event->sheet->getDelegate()->getStyle('A2')->getFont()->setSize(11);
            $event->sheet->getDelegate()->getStyle('A1:A3')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
            $event->sheet->getDelegate()->getStyle('A4')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_LEFT);
            $event->sheet->getDelegate()->getStyle('M4')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_RIGHT);
            $event->sheet->getDelegate()->getStyle('A4:M4')->getFont()->setBold(true);
            $event->sheet->getDelegate()->getStyle('A1:A3')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
            $event->sheet->getDelegate()->getStyle($cellRange)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
            $event->sheet->getDelegate()->getStyle('A6:A'.$highestRowNumber)->getFont()->setBold(true);
            $event->sheet->getDelegate()->getStyle('A6:A'.$highestRowNumber)->getAlignment()->setVertical(Alignment::VERTICAL_CENTER);
            $event->sheet->getDelegate()->getStyle('C6:C'.$highestRowNumber)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
            $event->sheet->getDelegate()->getStyle('I6:I'.$highestRowNumber)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_RIGHT);
            $event->sheet->getDelegate()->getStyle('J6:J'.$highestRowNumber)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_RIGHT);
            $event->sheet->getDelegate()->getStyle('K6:K'.$highestRowNumber)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_RIGHT);
            $event->sheet->getDelegate()->getStyle('L6:L'.$highestRowNumber)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_RIGHT);
            $event->sheet->getDelegate()->getStyle('M6:M'.$highestRowNumber)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_RIGHT);
            $event->sheet->getDelegate()->getStyle('N6:N'.$highestRowNumber)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_RIGHT);
            $event->sheet->getDelegate()->getStyle('O6:O'.$highestRowNumber)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_RIGHT);
            $event->sheet->getDelegate()->getStyle('P6:P'.$highestRowNumber)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_RIGHT);
            $event->sheet->getDelegate()->getStyle('A6:A'.$highestRowNumber)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
            $event->sheet->getDelegate()->getStyle('B6:B'.$highestRowNumber)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
            $event->sheet->getDelegate()->getStyle('D6:D'.$highestRowNumber)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
            $event->sheet->getDelegate()->getStyle('E6:E'.$highestRowNumber)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
            $event->sheet->getDelegate()->getStyle('F6:F'.$highestRowNumber)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
            $event->sheet->getDelegate()->getStyle('G6:G'.$highestRowNumber)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
            $event->sheet->getDelegate()->getStyle('H6:H'.$highestRowNumber)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
            $event->sheet->getDelegate()->getStyle('Q6:Q'.$highestRowNumber)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
            $event->sheet->getDelegate()->getStyle('R6:R'.$highestRowNumber)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
            $event->sheet->getDelegate()->getStyle('S6:S'.$highestRowNumber)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
            $event->sheet->getDelegate()->getStyle('T6:T'.$highestRowNumber)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
            $event->sheet->getDelegate()->getStyle('U6:U'.$highestRowNumber)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
            $event->sheet->getDelegate()->getStyle('V6:V'.$highestRowNumber)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
            $event->sheet->getDelegate()->getStyle('W6:W'.$highestRowNumber)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
        },
        ];
    }
}
