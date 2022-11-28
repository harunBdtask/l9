<?php

namespace SkylarkSoft\GoRMG\Subcontract\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithTitle;
use SkylarkSoft\GoRMG\Subcontract\PackageConst;

class DateWiseDeliveryReportExport implements WithTitle, ShouldAutoSize, FromView
{
    private $dyeingGoodsDelivery;

    public function __construct($dyeingGoodsDelivery)
    {
        $this->dyeingGoodsDelivery = $dyeingGoodsDelivery;
    }

    public function view(): View
    {
        return view(PackageConst::VIEW_PATH . 'report.date-wise-delivery-report.excel', [
            'dyeingGoodsDelivery' => $this->dyeingGoodsDelivery,
        ]);
    }

    public function title(): string
    {
        return "Date Wise Delivery Report";
    }
}
