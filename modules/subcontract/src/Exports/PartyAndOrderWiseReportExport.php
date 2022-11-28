<?php

namespace SkylarkSoft\GoRMG\Subcontract\Exports;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithTitle;
use SkylarkSoft\GoRMG\Subcontract\PackageConst;

class PartyAndOrderWiseReportExport implements WithTitle, ShouldAutoSize, FromView, ShouldQueue
{
    use Exportable;

    private $orderDetails;

    public function __construct($orderDetails)
    {
        $this->orderDetails = $orderDetails;
    }

    public function title(): string
    {
        return 'Dyeing Production Daily Report';
    }

    public function view(): View
    {
        $orderDetails = $this->orderDetails;

        return view(PackageConst::VIEW_PATH . 'report.excel.party_and_order_wise_report_excel', [
            'orderDetails' => $orderDetails,
        ]);
    }
}
