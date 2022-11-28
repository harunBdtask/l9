<?php

namespace SkylarkSoft\GoRMG\Iedroplets\Export;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\WithTitle;
use SkylarkSoft\GoRMG\Iedroplets\PackageConst;

class ShipmentReportExport implements FromView, WithTitle
{
    private $data;

    /**
     * ShipmentReport constructor.
     * @param $data
     */
    public function __construct($data)
    {
        $this->data = $data;
    }

    /**
     * @inheritDoc
     */
    public function view(): View
    {
        return view(PackageConst::PACKAGE_NAME . '::reports.includes.shipment_report_table', $this->data);
    }

    /**
     * @inheritDoc
     */
    public function title(): string
    {
        return 'Daily Shipment Report';
    }
}
