<?php

namespace SkylarkSoft\GoRMG\Knitting\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;

class YarnRequisitionListExcel implements WithTitle, FromView, WithEvents, ShouldAutoSize
{
    use Exportable;

    private $data;

    public function __construct($data)
    {
        $this->data = $data;
    }

    public function title(): string
    {
        return 'Yarn Requisition List';
    }

    public function view(): View
    {
        $data = $this->data;
        return view('knitting::yarn-requisition.excel', compact('data'));
    }

    public function registerEvents() : array
    {
        return [AfterSheet::class => function (AfterSheet $event) {
            $cellRange = 'A1:T1';
            $event->sheet->getDelegate()->getStyle($cellRange)->getFont()->setBold(true);
            $event->sheet->getDelegate()->getStyle('A3:T3')->getFont()->setBold(true);
            $event->sheet->getDelegate()->getStyle($cellRange)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
        }];
    }
}
