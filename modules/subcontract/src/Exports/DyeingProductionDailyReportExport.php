<?php

namespace SkylarkSoft\GoRMG\Subcontract\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithTitle;
use SkylarkSoft\GoRMG\Subcontract\PackageConst;

class DyeingProductionDailyReportExport implements WithTitle, ShouldAutoSize, FromView
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
        return view(PackageConst::VIEW_PATH . 'report.excel.dyeing_production_daily_report_excel', [
            'dyeingProduction' => $this->dyeingProduction,
        ]);
    }
}
