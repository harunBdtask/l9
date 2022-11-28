<?php

namespace SkylarkSoft\GoRMG\Subcontract\Exports;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithTitle;
use SkylarkSoft\GoRMG\Subcontract\PackageConst;

class OrderReportExport implements WithTitle, ShouldAutoSize, FromView, ShouldQueue
{
    use Exportable;

    public function __construct($order, $recipes)
    {
        $this->order = $order;
        $this->recipes = $recipes;
    }

    public function title(): string
    {
        return 'Order Report Excel';
    }

    public function view(): View
    {
        $order = $this->order;
        $recipes = $this->recipes;

        return view(PackageConst::VIEW_PATH . 'report.excel.order_report_excel', [
            'order' => $order,
            'recipes' => $recipes,
        ]);
    }
}
