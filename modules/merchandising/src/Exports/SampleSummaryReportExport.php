<?php

namespace SkylarkSoft\GoRMG\Merchandising\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithTitle;
use SkylarkSoft\GoRMG\Subcontract\PackageConst;

class SampleSummaryReportExport implements WithTitle, ShouldAutoSize, FromView
{
    private $samples;
    private $sampleStage;

    public function __construct($samples,$sampleStage)
    {
        $this->samples = $samples;
        $this->sampleStage = $sampleStage;
    }

    public function view(): View
    {
        return view("merchandising::reports.sample_summary_report.excel", [
            'samples' => $this->samples,
            'sampleStage' => $this->sampleStage,
        ]);
    }

    public function title(): string
    {
        return 'Sample Summary Report';
    }
}
