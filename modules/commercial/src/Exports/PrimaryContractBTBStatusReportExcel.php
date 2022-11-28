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

class PrimaryContractBTBStatusReportExcel implements WithTitle, ShouldAutoSize, FromView, ShouldQueue
{
    use Exportable;
    private $data;

    public function __construct($data)
    {
        $this->data = $data;
    }

    /**
     * @return string
     */
    public function title(): string
    {
        return 'Primary Contract BTB Status Report';
    }

    /**
     * @return View
     */
    public function view(): View
    {
        $data = $this->data;
        return view('commercial::btb-status-report.table', compact('data'));
    }

    public function registerEvents(): array
    {
        return [AfterSheet::class => function (AfterSheet $event) {
            $sheet = $event->getSheet();
            $sheet->autoSize(true);

            $event->sheet->getDelegate()->getStyle('A1:Q1')->getFont()->setSize('20')->setBold(true);
            $event->sheet->getDelegate()->getStyle('A1:Q1')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
        }];
    }
}
