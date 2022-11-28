<?php


namespace SkylarkSoft\GoRMG\ManualProduction\Exports;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;

class YearlyRejectionReportExport implements WithTitle, ShouldAutoSize, FromView, ShouldQueue, withEvents
{
    use Exportable;

    private $reports, $year;

    public function __construct($reports, $year)
    {
        $this->reports = $reports;
        $this->year = $year;
    }

    /**
     * @return string
     */
    public function title(): string
    {
        return 'Yearly Rejection Report';
    }

    /**
     * @return View
     */
    public function view(): View
    {
        $reports = $this->reports;
        $year = $this->year;

        return view('manual-production::reports.rejection.includes.yearly_rejection_report_include',
            compact('reports', 'year'));
    }

    /**
     * @return array
     */
    public function registerEvents(): array
    {
        return [AfterSheet::class => function (AfterSheet $event) {
            $cellRange = 'A2:N2'; //
            $highestRowNumber = $event->sheet->getDelegate()->getHighestRow();
            $event->sheet->getDelegate()->getStyle($cellRange)->getFont()->setBold(true);
            $event->sheet->getDelegate()->getStyle('A1:A3')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
            $event->sheet->getDelegate()->getStyle($cellRange)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
        }];
    }
}
