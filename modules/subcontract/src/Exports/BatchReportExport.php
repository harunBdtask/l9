<?php

namespace SkylarkSoft\GoRMG\Subcontract\Exports;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithTitle;
use SkylarkSoft\GoRMG\Subcontract\PackageConst;

class BatchReportExport implements WithTitle, ShouldAutoSize, FromView, ShouldQueue
{
    use Exportable;

    public function __construct($batch, $batchDetail)
    {
        $this->batch = $batch;
        $this->batchDetail = $batchDetail;
    }

    public function title(): string
    {
        return 'Batch Report';
    }

    public function view(): View
    {
        $batch = $this->batch;
        $batchDetail = $this->batchDetail;

        return view(PackageConst::VIEW_PATH . 'report.excel.batch_report_excel', [
            'batch' => $batch,
            'batchDetail' => $batchDetail,
        ]);
    }
}
