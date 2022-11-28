<?php

namespace SkylarkSoft\GoRMG\Dyeing\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\Exportable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

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
        return 'Party And Order Wise Report';
    }

    public function view(): View
    {
        $orderDetails = $this->orderDetails;

        return view('dyeing::reports.party-and-order-wise-report.party-and-order-wise-report-excel', [
            'orderDetails' => $orderDetails,
        ]);
    }
}
