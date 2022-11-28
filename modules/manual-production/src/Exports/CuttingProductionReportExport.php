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

class CuttingProductionReportExport implements WithTitle, ShouldAutoSize, FromView, ShouldQueue, withEvents
{
    use Exportable;

    private $reports;
    private $subcontract_factory_id;

    public function __construct($reports, $subcontract_factory_id)
    {
        $this->reports = $reports;
        $this->subcontract_factory_id = $subcontract_factory_id;
    }

    /**
     * @return string
     */
    public function title(): string
    {
        return 'Cutting Production Report';
    }

    /**
     * @return View
     */
    public function view(): View
    {
        $reports = $this->reports;
        $subcontract_factory_id = $this->subcontract_factory_id;

        return view('manual-production::reports.cutting.includes.date_wise_cutting_report_include', compact('reports', 'subcontract_factory_id'));
    }

    /**
     * @return array
     */
    public function registerEvents(): array
    {
        return [AfterSheet::class => function (AfterSheet $event) {
            $highestRowNumber = $event->sheet->getDelegate()->getHighestRow();
            $event->sheet->getDelegate()->getStyle('A1:A3')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
        }];
    }
}
