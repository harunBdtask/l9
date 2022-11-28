<?php

namespace SkylarkSoft\GoRMG\ManualProduction\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class ChallanWiseEmbrPrintExport implements FromView, ShouldAutoSize
{
    use Exportable;

    protected $order, $orders, $reports, $type;

    public function __construct($order, $orders, $reports, $type)
    {
        $this->order = $order;
        $this->orders = $orders;
        $this->reports = $reports;
        $this->type = $type;
    }

    public function view(): View
    {
        $order = $this->order;
        $orders = $this->orders;
        $reports = $this->reports;
        $viewPath = "";
        if ($this->type == 'embr') {
            $viewPath = "manual-production::reports.print.includes.challan_wise_embr_report_include";
        } else {
            $viewPath = "manual-production::reports.print.includes.challan_wise_print_report_include";
        }
        return view($viewPath, compact('order', 'orders', 'reports'));
    }
}
