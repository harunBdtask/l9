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

class DateWiseFinishingReportExport implements WithTitle, ShouldAutoSize, FromView, ShouldQueue, WithEvents
{
    use Exportable;

    private $result_data;
    private $buyer_wise_count;
    private $order_wise_count;
    private $color_wise_count;
    private $size_wise_count;


    public function __construct($data)
    {
        $this->result_data = $data;
        $this->buyer_wise_count = $data['buyer_wise_count'];
        $this->order_wise_count = $data['order_wise_count'];
        $this->color_wise_count = $data['color_wise_count'];
        $this->size_wise_count = $data['size_wise_count'];
    }

    /**
     * @return string
     */
    public function title(): string
    {
        return 'Date Wise Finishing Report';
    }


    /**
     * @return View
     */
    public function view(): View
    {
        $data = $this->result_data;
        return view('finishingdroplets::reports.downloads.excels.date-wise-finishing-report-download', $data);
    }

    /**
     * @return array
     */
    public function registerEvents(): array
    {
        return [ AfterSheet::class => function(AfterSheet $event){

            $event->sheet->getDelegate()->getStyle('A1')->getFont()->setSize(14);
            $event->sheet->getDelegate()->getStyle('A1:B4')->getFont()->setBold(true);
            $event->sheet->getDelegate()->getStyle('A1:B4')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
            $buyerWise = $this->buyer_wise_count;
            $buyerWiseStart = $buyerWise + 4;
            $event->sheet->getDelegate()->getStyle('A5:A'.($buyerWise))->getAlignment()->setHorizontal(Alignment::HORIZONTAL_LEFT);
            $event->sheet->getDelegate()->getStyle('B5:B'.($buyerWise))->getAlignment()->setHorizontal(Alignment::HORIZONTAL_RIGHT);
            $event->sheet->getDelegate()->getStyle('A'.($buyerWiseStart+1) .':B'.($buyerWiseStart + 1 ))->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
            $event->sheet->getDelegate()->getStyle('A'.($buyerWiseStart+1) .':B'.($buyerWiseStart + 1 ))->getFont()->setBold(true);

            $orderWise = $this->order_wise_count;
            $orderWiseStart = $buyerWise + 1 + 4 + 2 ;
            $event->sheet->getDelegate()->getStyle('A'.$orderWiseStart.':D'.($orderWiseStart + 1))->getFont()->setBold(true);
            $event->sheet->getDelegate()->getStyle('A'.$orderWiseStart.':D'.($orderWiseStart + 1))->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
            $event->sheet->getDelegate()->getStyle('A'.($orderWiseStart+2).':C'.($orderWiseStart + 2 + $orderWise))->getAlignment()->setHorizontal(Alignment::HORIZONTAL_LEFT);
            $event->sheet->getDelegate()->getStyle('D'.($orderWiseStart+2).':D'.($orderWiseStart + 2 + $orderWise))->getAlignment()->setHorizontal(Alignment::HORIZONTAL_RIGHT);
            $event->sheet->getDelegate()->getStyle('A'.($orderWiseStart + 2 +$orderWise).':D'.($orderWiseStart + 2 + $orderWise))->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
            $event->sheet->getDelegate()->getStyle('A'.($orderWiseStart + 2 +$orderWise).':D'.($orderWiseStart + 2 + $orderWise))->getFont()->setBold(true);

            $colorWise = $this->color_wise_count;
            $colorWiseStart = $orderWiseStart + 3 +$orderWise;
            $event->sheet->getDelegate()->getStyle('A'.($colorWiseStart + 1 ).':E'.($colorWiseStart + 2))->getFont()->setBold(true);
            $event->sheet->getDelegate()->getStyle('A'.($colorWiseStart + 1 ) .':E'.($colorWiseStart + 2))->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
            $event->sheet->getDelegate()->getStyle('A'.($colorWiseStart + 3).':D'.($colorWiseStart + 3+$colorWise))->getAlignment()->setHorizontal(Alignment::HORIZONTAL_LEFT);
            $event->sheet->getDelegate()->getStyle('E'.($colorWiseStart + 3).':E'.($colorWiseStart + 3+$colorWise))->getAlignment()->setHorizontal(Alignment::HORIZONTAL_RIGHT);
            $event->sheet->getDelegate()->getStyle('A'.($colorWiseStart + 3 + $colorWise).':E'.($colorWiseStart + 3 + $colorWise))->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
            $event->sheet->getDelegate()->getStyle('A'.($colorWiseStart + 3 + $colorWise).':E'.($colorWiseStart + 3 + $colorWise))->getFont()->setBold(true);

            $sizeWise = $this->size_wise_count;
            $sizeWiseStart = $colorWiseStart + 5 + $colorWise;
            $event->sheet->getDelegate()->getStyle('A'.$sizeWiseStart.':F'.($sizeWiseStart + 1))->getFont()->setBold(true);
            $event->sheet->getDelegate()->getStyle('A'.$sizeWiseStart.':F'.($sizeWiseStart + 1))->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
            $event->sheet->getDelegate()->getStyle('A'.($sizeWiseStart + 2).':E'.($sizeWiseStart + 2 + $sizeWise))->getAlignment()->setHorizontal(Alignment::HORIZONTAL_LEFT);
            $event->sheet->getDelegate()->getStyle('F'.($sizeWiseStart + 2).':F'.($sizeWiseStart + 2 + $sizeWise))->getAlignment()->setHorizontal(Alignment::HORIZONTAL_RIGHT);
            $event->sheet->getDelegate()->getStyle('A'.($sizeWiseStart + 2 + $sizeWise).':F'.($sizeWiseStart + 2 + $sizeWise))->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
            $event->sheet->getDelegate()->getStyle('A'.($sizeWiseStart + 2 + $sizeWise).':F'.($sizeWiseStart + 2 + $sizeWise))->getFont()->setBold(true);













        } ];
    }
}