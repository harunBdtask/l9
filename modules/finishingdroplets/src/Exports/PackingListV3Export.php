<?php

namespace SkylarkSoft\GoRMG\Finishingdroplets\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class PackingListV3Export implements ShouldAutoSize, FromView
{
    private $viewData;

    public function __construct($viewData)
    {
        $this->viewData = $viewData;
    }

    public function view(): View
    {
        return view('finishingdroplets::finishing-packing-list-v3.view.excel',
            [
                'garmentPackingProduction' => $this->viewData
            ]);
    }
}
