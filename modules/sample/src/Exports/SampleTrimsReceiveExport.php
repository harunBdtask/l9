<?php

namespace SkylarkSoft\GoRMG\Sample\Exports;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;

class SampleTrimsReceiveExport implements WithTitle, ShouldAutoSize, FromView, ShouldQueue, withEvents
{
    use Exportable;

    private $sampleTrimsReceive;

    public function __construct($sampleTrimsReceive)
    {
        $this->sampleTrimsReceive = $sampleTrimsReceive;
    }

    /**
     * @return string
     */
    public function title(): string
    {
        return 'Sample Trims Issue Excel';
    }

    /**
     * @return View
     */
    public function view(): View
    {
        $sampleTrimsReceive = $this->sampleTrimsReceive;

        return view('sample::trims-receive.excel', compact('sampleTrimsReceive'));
    }

    /**
     * @return array
     */
    public function registerEvents(): array
    {
        return [AfterSheet::class => function (AfterSheet $event) {
            $cellRange = 'A1:H1';
            $getHighestRow = $event->sheet->getDelegate()->getHighestRow();
            $event->sheet->getDelegate()->getStyle($cellRange)->getFont()->setBold(true);
            $event->sheet->getDelegate()->getStyle('A1:H' . $getHighestRow)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
        }];
    }
}
