<?php

namespace SkylarkSoft\GoRMG\Merchandising\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;

class OrderExcel implements WithTitle, FromView, WithEvents, ShouldAutoSize
{
    use Exportable;

    private $orders;

    public function __construct($orders)
    {
        $this->orders = $orders;
    }

    public function title(): string
    {
        return 'Order List View';
    }

    public function view(): View
    {
        $orders = $this->orders;
        return view('merchandising::order.order-list-excel', compact('orders'));
    }

    public function registerEvents() : array
    {
        return [AfterSheet::class => function (AfterSheet $event) {
            $cellRange = 'A1:N1';
            $getHighestRow = $event->sheet->getDelegate()->getHighestRow();
            $event->sheet->getDelegate()->getStyle($cellRange)->getFont()->setBold(true);
            $event->sheet->getDelegate()->getStyle('A3:N3')->getFont()->setBold(true);
            $event->sheet->getDelegate()->getStyle('A1:N1')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
            $event->sheet->getDelegate()->getStyle('A3:N'.$getHighestRow)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
            $event->sheet->getDelegate()->getStyle('K4:K'.$getHighestRow)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_LEFT);
            $event->sheet->getDelegate()->getStyle('C4:C'.$getHighestRow)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_LEFT);
            $event->sheet->getDelegate()->getStyle('E4:E'.$getHighestRow)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_LEFT);
            $event->sheet->getDelegate()->getStyle('L4:L'.$getHighestRow)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_LEFT);
        }];
    }
}
