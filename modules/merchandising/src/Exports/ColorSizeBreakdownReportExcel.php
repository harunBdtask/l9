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

class ColorSizeBreakdownReportExcel implements WithTitle, ShouldAutoSize, FromView, ShouldQueue, WithEvents
{
    use Exportable;

    private $pos, $fromDate, $toDate, $factoryId, $buyerId, $jobNo, $request, $type;

    public function __construct($pos, $fromDate, $toDate, $factoryId, $buyerId, $jobNo, $request, $type)
    {
        $this->pos = $pos;
        $this->fromDate = $fromDate;
        $this->toDate = $toDate;
        $this->factoryId = $factoryId;
        $this->buyerId = $buyerId;
        $this->jobNo = $jobNo;
        $this->request = $request;
        $this->type = $type;
    }

    /**
     * @return string
     */
    public function title(): string
    {
        return 'Color Size Breakdown Report';
    }

    /**
     * @return View
     */
    public function view(): View
    {
        $pos = $this->pos;
        $fromDate = $this->fromDate;
        $toDate = $this->toDate;
        $factoryId = $this->factoryId;
        $buyerId = $this->buyerId;
        $jobNo = $this->jobNo;
        $request = $this->request;
        $type = $this->type;

        return view('merchandising::order.report.color_size_breakdown_details_breakdown', compact('pos', 'fromDate', 'toDate', 'factoryId', 'buyerId', 'jobNo', 'request', 'type'));
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