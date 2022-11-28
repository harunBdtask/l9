<?php

namespace SkylarkSoft\GoRMG\Commercial\Exports;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;

class PITrackingReportExcel implements WithTitle, ShouldAutoSize, FromView, ShouldQueue
{
    use Exportable;

    private $reportData, $buyerName;

    public function __construct($data, $buyer)
    {
        $this->reportData = $data;
        $this->buyerName = $buyer;
    }

    /**
     * @return string
     */
    public function title(): string
    {
        return 'Performance Report';
    }

    /**
     * @return View
     */
    public function view(): View
    {
        return view('commercial::reports.pi-tracking-report.excel', [
            'reportData' => $this->reportData,
            'buyerName' => $this->buyerName,
        ]);
    }

    /**
     * @return array
     */
    public function registerEvents(): array
    {
        return [AfterSheet::class => function (AfterSheet $event) {
            $cellRange = 'A1:K1';
            $getHighestRow = $event->sheet->getDelegate()->getHighestRow();
            $event->sheet->getDelegate()->getStyle($cellRange)->getFont()->setBold(true);
            $event->sheet->getDelegate()->getStyle('A1:H' . $getHighestRow)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
        }];
    }
}
