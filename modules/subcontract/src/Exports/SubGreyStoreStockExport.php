<?php

namespace SkylarkSoft\GoRMG\Subcontract\Exports;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithTitle;
use SkylarkSoft\GoRMG\Subcontract\PackageConst;

class SubGreyStoreStockExport implements WithTitle, ShouldAutoSize, FromView, ShouldQueue
{
    use Exportable;

    private $stockSummery;

    public function __construct($stockSummery)
    {
        $this->stockSummery = $stockSummery;
    }

    public function title(): string
    {
        return 'Sub Grey Store Stock Summery';
    }

    public function view(): View
    {
        $stockSummery = $this->stockSummery;

        return view(PackageConst::VIEW_PATH . 'report.excel.sub_grey_store_view_excel', [
            'stockSummery' => $stockSummery,
        ]);
    }
}
