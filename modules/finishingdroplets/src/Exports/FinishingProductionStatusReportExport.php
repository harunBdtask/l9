<?php

namespace SkylarkSoft\GoRMG\Finishingdroplets\Exports;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;

class FinishingProductionStatusReportExport implements WithTitle, ShouldAutoSize, FromView, ShouldQueue, WithEvents
{
    use Exportable;

    private $finishing_production_report, $buyer, $order_style_no;

    public function __construct($finishing_production_report, $buyer, $order_style_no)
    {
        $this->finishing_production_report = $finishing_production_report;
        $this->buyer = $buyer;
        $this->order_style_no = $order_style_no;
    }

    /**
     * @return string
     */
    public function title(): string
    {
        return 'Finishing Status Report';
    }


    /**
     * @return View
     */
    public function view(): View
    {
        $finishing_production_report = $this->finishing_production_report;
        $buyer = $this->buyer;
        $order_style_no = $this->order_style_no;
        return view('finishingdroplets::reports.downloads.excels.finishing_production_status_download', compact('finishing_production_report', 'buyer', 'order_style_no'));
    }

    /**
     * @return array
     */
    public function registerEvents(): array
    {
        return [ AfterSheet::class => function( AfterSheet $event ){
            $getHighestRow = $event->sheet->getDelegate()->getHighestRow();
            $event->sheet->getDelegate()->getStyle('A1')->getFont()->setSize(14);
            $event->sheet->getDelegate()->getStyle('A1:M4')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
            $event->sheet->getDelegate()->getStyle('A1:M4')->getFont()->setBold(true);
            $event->sheet->getDelegate()->getStyle('A5:A'.($getHighestRow-1))->getAlignment()->setHorizontal(Alignment::HORIZONTAL_LEFT);
            $event->sheet->getDelegate()->getStyle('B5:K'.($getHighestRow-1))->getAlignment()->setHorizontal(Alignment::HORIZONTAL_RIGHT);
            $event->sheet->getDelegate()->getStyle('L5:M'.($getHighestRow-1))->getAlignment()->setHorizontal(Alignment::HORIZONTAL_LEFT);
            $event->sheet->getDelegate()->getStyle('A'.($getHighestRow).':L'.($getHighestRow))->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
            $event->sheet->getDelegate()->getStyle('A'.($getHighestRow).':L'.($getHighestRow))->getFont()->setBold(true);
        } ];
    }
}