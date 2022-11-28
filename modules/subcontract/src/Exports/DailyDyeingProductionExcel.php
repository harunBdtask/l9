<?php

namespace SkylarkSoft\GoRMG\Subcontract\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithTitle;
use SkylarkSoft\GoRMG\Subcontract\PackageConst;

class DailyDyeingProductionExcel implements WithTitle, ShouldAutoSize, FromView
{
    private $dyeingProduction;

    public function __construct($dyeingProduction)
    {
        $this->dyeingProduction = $dyeingProduction;
    }

    public function title(): string
    {
        return "Daily Dyeing Production";
    }

    public function view(): View
    {
        return view(PackageConst::VIEW_PATH . 'report.dyeing-production.daily.excel', $this->dyeingProduction);
    }
}
