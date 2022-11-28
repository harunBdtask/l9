<?php

namespace SkylarkSoft\GoRMG\Merchandising\Exports;

use Illuminate\Contracts\Queue\ShouldQueue;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithTitle;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;

class SeasonWiseOrderOverviewExport implements WithTitle, ShouldAutoSize, FromView, ShouldQueue, withEvents
{
    use Exportable;

    private $reportData;

    public function __construct($data)
    {
        $this->reportData = $data;
    }

    /**
     * @return string
     */
    public function title(): string
    {
        return 'Buyer Season Order List';
    }

    /**
     * @return View
     */
    public function view(): View
    {
        $orders = $this->reportData;
        return view('merchandising::order.overview_report.season_wise_order_overview_excel', compact('orders'));
    }

    /**
     * @return array
     */
    public function registerEvents(): array
    {
        return [AfterSheet::class => function (AfterSheet $event) {
            $cellRange = 'A1:D1';
            $highestRowNumber = $event->sheet->getDelegate()->getHighestRow();
            $event->sheet->getDelegate()->getStyle($cellRange)->getFont()->setBold(true);
            $event->sheet->getDelegate()->getStyle($highestRowNumber)->getFont()->setBold(true);
        },
        ];
    }
}
