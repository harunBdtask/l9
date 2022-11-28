<?php

namespace SkylarkSoft\GoRMG\Knitting\Exports;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithTitle;

class FabricSalesOrderViewV2Export implements ShouldAutoSize, FromView, ShouldQueue
{
    use Exportable;

    
    private $data;

    public function __construct($data)
    {
        $this->data = $data;
       
    }


    public function view(): View
    {
        $data = $this->data;
        return view('knitting::fabricSalesOrder.view-body-v2', $data);
    }
}
