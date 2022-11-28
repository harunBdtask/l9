<?php

namespace SkylarkSoft\GoRMG\Inventory\Exports\TrimsStore;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithTitle;

class TrimsStoreDeliveryChallanReportExport implements WithTitle, ShouldAutoSize, FromView, ShouldQueue
{
    use Exportable;

    private $challanNo;
    private $deliveryChallan;
    private $details;

    public function __construct($challanNo, $deliveryChallan, $details)
    {
        $this->challanNo = $challanNo;
        $this->deliveryChallan = $deliveryChallan;
        $this->details = $details;
    }

    /**
     * @return string
     */
    public function title(): string
    {
        return 'Trims Store Delivery Challan Report';
    }

    /**
     * @return View
     */
    public function view(): View
    {
        $challanNo = $this->challanNo;
        $deliveryChallans = $this->deliveryChallan;
        $details = $this->details;

        return view('inventory::trims-store.trims-delivery-challan.excel', compact('challanNo', 'deliveryChallans', 'details'));
    }
}
