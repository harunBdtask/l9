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

class BuyerSeasonOrderListExport implements WithTitle, ShouldAutoSize, FromView, ShouldQueue, withEvents
{
    use Exportable;

    private $reportData, $type;

    public function __construct($data, $type = null)
    {
        $this->reportData = $data;
        $this->type = $type;
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
        if ($this->type === 'color') {
            return view('merchandising::reports.includes.buyer_season_color_order_table', $this->reportData, ['type' => $this->type]);
        } else {
            return view('merchandising::reports.includes.buyer_season_order_report_table', $this->reportData);
        }
    }

    /**
     * @return array
     */
    public function registerEvents(): array
    {
        return [AfterSheet::class => function (AfterSheet $event) {
            $cellRange = 'A1:P1';
            $highestRowNumber = $event->sheet->getDelegate()->getHighestRow();
            $event->sheet->getDelegate()->getStyle($cellRange)->getFont()->setBold(true);
            $event->sheet->getDelegate()->getStyle($highestRowNumber)->getFont()->setBold(true);
        },
        ];
    }
}
