<?php

namespace SkylarkSoft\GoRMG\Merchandising\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;

class SampleExcel implements WithTitle, FromView, WithEvents
{
    use Exportable;

    private $samples;

    public function __construct($samples)
    {
        $this->samples = $samples;
    }

    public function title(): string
    {
        return 'Samples List View';
    }

    public function view(): View
    {
        $samples = $this->samples;
        return view('merchandising::samples.sample-list-excel', compact('samples'));
    }

    public function registerEvents() : array
    {
        return [AfterSheet::class => function (AfterSheet $event) {
            $cellRange = 'A1:H1';
            $getHighestRow = $event->sheet->getDelegate()->getHighestRow();
            $event->sheet->getDelegate()->getStyle($cellRange)->getFont()->setBold(true);
            $event->sheet->getDelegate()->getStyle($cellRange)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
            $event->sheet->getDelegate()->getStyle('A3:H3')->getFont()->setBold(true);
            $event->sheet->getDelegate()->getStyle('A1:H'.$getHighestRow)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
            $event->sheet->getDelegate()->getStyle('D4:D'.$getHighestRow)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_LEFT);
            $event->sheet->getDelegate()->getStyle('E4:E'.$getHighestRow)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_LEFT);
            $event->sheet->getDelegate()->getStyle('G4:G'.$getHighestRow)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_LEFT);

        }];
    }
}
