<?php

namespace SkylarkSoft\GoRMG\Finishingdroplets\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class PackingListV2Export implements ShouldAutoSize, FromView
{
    private $viewData;

    public function __construct($viewData)
    {
        $this->viewData = $viewData;
    }

    public function view(): View
    {
        return view('finishingdroplets::erp-packing-list-v2.excel',
            $this->viewData);
    }
}
