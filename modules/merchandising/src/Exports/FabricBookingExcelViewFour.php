<?php

namespace SkylarkSoft\GoRMG\Merchandising\Exports;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithTitle;

class FabricBookingExcelViewFour implements WithTitle, ShouldAutoSize, FromView, ShouldQueue
{
    use Exportable;

    private $fabricBookings, $collarDetails, $cuffDetails, $yarnDetails, $collarStripDetails, $cuffStripDetails, $sortedShipmentDate;

    public function __construct($data)
    {
        $this->fabricBookings = $data['fabricBookings'];
        $this->collarDetails = $data['collarDetails'];
        $this->cuffDetails = $data['cuffDetails'];
        $this->yarnDetails = $data['yarnDetails'];
        $this->collarStripDetails = $data['collarStripDetails'];
        $this->cuffStripDetails = $data['cuffStripDetails'];
        $this->sortedShipmentDate = $data['sortedShipmentDate'];
    }

    /**
     * @return string
     */
    public function title(): string
    {
        return 'Fabric Booking Excel View Four';
    }

    /**
     * @return View
     */
    public function view(): View
    {
        $fabricBookings = $this->fabricBookings;
        $collarDetails = $this->collarDetails;
        $cuffDetails = $this->cuffDetails;
        $yarnDetails = $this->yarnDetails;
        $collarStripDetails = $this->collarStripDetails;
        $cuffStripDetails = $this->cuffStripDetails;
        $sortedShipmentDate = $this->sortedShipmentDate;
        return view('merchandising::fabric-bookings.view-4.excel', compact('fabricBookings', 'collarDetails','cuffDetails','yarnDetails','collarStripDetails','cuffStripDetails','sortedShipmentDate'));
    }
}
