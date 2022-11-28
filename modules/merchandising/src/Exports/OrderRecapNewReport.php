<?php

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


class OrderRecapNewReport implements WithTitle, ShouldAutoSize, FromView, ShouldQueue, withEvents
{
    use Exportable;

    private $orderData;
    private $summaryData;
    private $type;

    public function __construct($orderData, $summaryData, $type)
    {
        $this->orderData = $orderData;
        $this->summaryData = $summaryData;
        $this->type = $type;
    }

    /**
     * @return string
     */
    public function title(): string
    {
        return 'Order Recap Report';
    }

    /**
     * @return View
     */
    public function view(): View
    {
        $orderData = $this->orderData;
        $summaryData = $this->summaryData;
        $type = $this->type;
        return view('merchandising::new-report.order-recap.table', compact('orderData', 'summaryData', 'type'));
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
