<?php

namespace SkylarkSoft\GoRMG\Sewingdroplets\Exports;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;


class DateRangeWiseOutputReportExport implements WithTitle, ShouldAutoSize, FromView, ShouldQueue, WithEvents
{
    use Exportable;

    private $result_data;
    private $section_one;
    private $section_two;
    private $section_three;
    private $section_four;
    public function __construct($data)
    {
        $this->result_data = $data;
        $this->section_one= $data['section_1_count'];
        $this->section_two = $data['section_2_count'];
        $this->section_three = $data['section_3_count'];
        $this->section_four = $data['section_4_count'];
    }

    /**
     * @return string
     */
    public function title(): string
    {
        return 'Date Wise Sewing Report';
    }


    /**
     * @return View
     */
    public function view(): View
    {
        $data = $this->result_data;
        return view('sewingdroplets::reports.downloads.excels.date-range-wise-sewing-report-download', $data);
    }

    /**
     * @return array
     */
    public function registerEvents(): array
    {
        return [ AfterSheet::class => function(AfterSheet $event){
           $getHighestRow = $event->sheet->getDelegate()->getHighestRow();
           $sectionOneCount = $this->section_one;
           $sectionTwoCount = $this->section_two;
           $sectionThreeCount = $this->section_three;
           $sectionFourCount = $this->section_four;
            $event->sheet->getDelegate()->getStyle('A1')->getFont()->setSize(14);
            $event->sheet->getDelegate()->getStyle('A1:G4')->getFont()->setBold(true);
            $event->sheet->getDelegate()->getStyle('A1:G4')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
            $event->sheet->getDelegate()->getStyle('A5:E'.($sectionOneCount +4))->getAlignment()->setHorizontal(Alignment::HORIZONTAL_LEFT);
            $event->sheet->getDelegate()->getStyle('F5:G'.($sectionOneCount +4))->getAlignment()->setHorizontal(Alignment::HORIZONTAL_RIGHT);
            $event->sheet->getDelegate()->getStyle('A'.($sectionOneCount+5).':G'.($sectionOneCount+5))->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
            $event->sheet->getDelegate()->getStyle('A'.($sectionOneCount+5).':G'.($sectionOneCount+5))->getFont()->setBold(true);
            $startSectionTwo = $sectionOneCount + 7;
            $event->sheet->getDelegate()->getStyle('A'.$startSectionTwo.':D'.($startSectionTwo +1))->getFont()->setBold(true);
            $event->sheet->getDelegate()->getStyle('A'.$startSectionTwo.':D'.($startSectionTwo +1))->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
            $event->sheet->getDelegate()->getStyle('A'.($startSectionTwo + 2 ).':B'.($startSectionTwo + 2 + $sectionTwoCount))->getAlignment()->setHorizontal(Alignment::HORIZONTAL_LEFT);
            $event->sheet->getDelegate()->getStyle('C'.($startSectionTwo + 2 ).':D'.($startSectionTwo + 2 + $sectionTwoCount))->getAlignment()->setHorizontal(Alignment::HORIZONTAL_RIGHT);
            $event->sheet->getDelegate()->getStyle('A'.($startSectionTwo + 2 + $sectionTwoCount).':D'.($startSectionTwo + 2 + $sectionTwoCount))->getFont()->setBold(true);
            $event->sheet->getDelegate()->getStyle('A'.($startSectionTwo + 2 + $sectionTwoCount).':D'.($startSectionTwo + 2 + $sectionTwoCount))->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
            $startSectionThree = $startSectionTwo +  $sectionTwoCount + 4;
            $event->sheet->getDelegate()->getStyle('A'.$startSectionThree.':E'.($startSectionThree +1 ))->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
            $event->sheet->getDelegate()->getStyle('A'.$startSectionThree.':E'.($startSectionThree +1))->getFont()->setBold(true);
            $event->sheet->getDelegate()->getStyle('A'.($startSectionThree + 2) .':C'.($startSectionThree +1+ $sectionThreeCount ))->getAlignment()->setHorizontal(Alignment::HORIZONTAL_LEFT);
            $event->sheet->getDelegate()->getStyle('D'.($startSectionThree + 2) .':E'.($startSectionThree +1+ $sectionThreeCount ))->getAlignment()->setHorizontal(Alignment::HORIZONTAL_RIGHT);
            $event->sheet->getDelegate()->getStyle('A'.($startSectionThree +2+ $sectionThreeCount ) .':E'.($startSectionThree +2+ $sectionThreeCount ))->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
            $event->sheet->getDelegate()->getStyle('A'.($startSectionThree +2+ $sectionThreeCount ) .':E'.($startSectionThree +2+ $sectionThreeCount ))->getFont()->setBold(true);
            $startSectionFour = $startSectionThree + 4 + $sectionThreeCount;
            $event->sheet->getDelegate()->getStyle('A'.$startSectionFour.':F'.($startSectionFour+1) )->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
            $event->sheet->getDelegate()->getStyle('A'.$startSectionFour.':F'.($startSectionFour+1) )->getFont()->setBold(true);
            $event->sheet->getDelegate()->getStyle('A'.($startSectionFour + 2).':D'.($startSectionFour+1 + $sectionFourCount) )->getAlignment()->setHorizontal(Alignment::HORIZONTAL_LEFT);
            $event->sheet->getDelegate()->getStyle('E'.($startSectionFour + 2).':F'.($startSectionFour+ 1 + $sectionFourCount) )->getAlignment()->setHorizontal(Alignment::HORIZONTAL_RIGHT);
            $event->sheet->getDelegate()->getStyle('A'.($startSectionFour+ 2 + $sectionFourCount).':F'.($startSectionFour+ 2 + $sectionFourCount))->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
            $event->sheet->getDelegate()->getStyle('A'.($startSectionFour+ 2 + $sectionFourCount).':F'.($startSectionFour+ 2 + $sectionFourCount))->getFont()->setBold(true);









        } ];
    }
}