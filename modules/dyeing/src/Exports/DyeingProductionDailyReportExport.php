<?php

namespace SkylarkSoft\GoRMG\Dyeing\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\Exportable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class DyeingProductionDailyReportExport implements WithTitle, ShouldAutoSize, FromView, ShouldQueue
{
    use Exportable;

    private $dyeingProduction;

    public function __construct($dyeingProduction)
    {
        $this->dyeingProduction = $dyeingProduction;
    }

    public function title(): string
    {
        return 'Dyeing Production Daily Report';
    }

    public function view(): View
    {
        $dyeingProduction = $this->dyeingProduction;

        return view('dyeing::reports.dyeing-production-daily-report.dyeing-production-daily-report-excel', [
            'dyeingProduction' => $dyeingProduction,
        ]);
    }
}
