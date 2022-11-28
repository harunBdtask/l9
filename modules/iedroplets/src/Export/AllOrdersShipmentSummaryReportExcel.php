<?php

namespace SkylarkSoft\GoRMG\Iedroplets\Export;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Events\AfterSheet;
use SkylarkSoft\GoRMG\Iedroplets\PackageConst;

class AllOrdersShipmentSummaryReportExcel implements WithTitle, ShouldAutoSize, FromView, ShouldQueue, withEvents
{
    use Exportable;

    private $resultData;

    public function __construct($data)
    {
        $this->resultData = $data;
    }

    /**
     * @return string
     */
    public function title(): string
    {
        return 'Shipment Summary';
    }

    /**
     * @return View
     */
    public function view(): View
    {
        $data = $this->resultData;
        return view(PackageConst::PACKAGE_NAME . '::reports.order_wise_shipment_report_table', $data);
    }

    /**
     * @return array
     */
    public function registerEvents(): array
    {
        return [AfterSheet::class => function (AfterSheet $event) {
        }];
    }
}
