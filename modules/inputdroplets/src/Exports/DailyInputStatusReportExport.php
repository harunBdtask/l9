<?php

namespace SkylarkSoft\GoRMG\Inputdroplets\Exports;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;

class DailyInputStatusReportExport implements WithTitle, ShouldAutoSize, FromView, ShouldQueue, WithEvents
{
    use Exportable;

    private $tableStartRow = 4;
    private $resultData;
    private $increaseLine;

    public function __construct($data)
    {
        $this->resultData = $data;
        $this->increaseLine = $this->tableStartRow;
    }

    /**
     * @return string
     */
    public function title(): string
    {
        return 'Daily input status Report';
    }


    /**
     * @return View
     */
    public function view(): View
    {
        $data = $this->resultData;
        return view('inputdroplets::reports.downloads.excels.daily-input-status-report', $data);
    }

    /**
     * @return array
     */
    public function registerEvents(): array
    {
        return [AfterSheet::class => function (AfterSheet $event) {
            $event->sheet->getDelegate()->getStyle('A1:G1')->getFont()->setBold(true);
            $event->sheet->getDelegate()->getStyle('A1:G1')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

            $event->sheet->getDelegate()->getStyle('A2:G2')->getFont()->setBold(true);
            $event->sheet->getDelegate()->getStyle('A2:G2')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

            $event->sheet->getDelegate()->getStyle('A4:G4')->getFont()->setBold(true);
            $event->sheet->getDelegate()->getStyle('A4:G4')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

            foreach ($this->resultData['daily_input_status']->groupBy('buyer_id') as $key => $dailyInputStatus) {
                $this->increaseLine += count($dailyInputStatus) + 1;
                $event->sheet->getDelegate()->getStyle("A{$this->increaseLine}:G{$this->increaseLine}")->getFont()->setBold(true);
                $event->sheet->getDelegate()->getStyle("A{$this->increaseLine}:G{$this->increaseLine}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
            }

            $lastRow = count($this->resultData['daily_input_status']) + count($this->resultData['daily_input_status']->groupBy('buyer_id')) + $this->tableStartRow + 1;

            $event->sheet->getDelegate()->getStyle("A{$lastRow}:G{$lastRow}")->getFont()->setBold(true);
            $event->sheet->getDelegate()->getStyle("A{$lastRow}:G{$lastRow}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
        }];
    }

}