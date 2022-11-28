<?php

namespace SkylarkSoft\GoRMG\Inputdroplets\Exports;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;

class DateWiseInputReportExport implements WithTitle, ShouldAutoSize, FromView, ShouldQueue, WithEvents
{
    use Exportable;

    private $result_data;
    private  $lineCount;

    public function __construct($data)
    {
        $this->result_data = $data;
        $this->lineCount = $data['line_wise_count'];
    }

    /**
     * @return string
     */
    public function title(): string
    {
        return 'Date Wise Input Report';
    }


    /**
     * @return View
     */
    public function view(): View
    {
        $data = $this->result_data;
        return view('inputdroplets::reports.downloads.excels.date-wise-sewing-input-download', $data);
    }

    /**
     * @return array
     */
    public function registerEvents(): array
    {
        return [ AfterSheet::class => function ( AfterSheet $event ){
        $lineCount = $this->lineCount;
        $getHighestRow = $event->sheet->getDelegate()->getHighestRow();
        $getTotalChallanNo = $getHighestRow - $lineCount - 4;
        $lineWiseInput = $getTotalChallanNo + 2;
        $event->sheet->getDelegate()->getStyle('A'.$getTotalChallanNo.':G'.$getTotalChallanNo)->getFont()->setBold(true);
        $event->sheet->getDelegate()->getStyle('A1:H4')->getFont()->setBold(true);
        $event->sheet->getDelegate()->getStyle('A1:H4')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
        $event->sheet->getDelegate()->getStyle('A1')->getFont()->setSize(14);
        $event->sheet->getDelegate()->getStyle('A5:F'.($getTotalChallanNo-1))->getAlignment()->setHorizontal(Alignment::HORIZONTAL_LEFT);
        $event->sheet->getDelegate()->getStyle('G5:G'.($getTotalChallanNo-1))->getAlignment()->setHorizontal(Alignment::HORIZONTAL_RIGHT);
        $event->sheet->getDelegate()->getStyle('A'.$lineWiseInput.':C'.($lineWiseInput+1))->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
        $event->sheet->getDelegate()->getStyle('A'.$lineWiseInput.':C'.($lineWiseInput+1))->getFont()->setBold(true);
        $event->sheet->getDelegate()->getStyle('A'.($lineWiseInput+2).':B'.($getHighestRow-1))->getAlignment()->setHorizontal(Alignment::HORIZONTAL_LEFT);
        $event->sheet->getDelegate()->getStyle('C'.($lineWiseInput+2).':C'.($getHighestRow-1))->getAlignment()->setHorizontal(Alignment::HORIZONTAL_RIGHT);
        $event->sheet->getDelegate()->getStyle('A'.$getHighestRow.':C'.$getHighestRow)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
        $event->sheet->getDelegate()->getStyle('A'.$getHighestRow.':C'.$getHighestRow)->getFont()->setBold(true);






        }];
    }
}