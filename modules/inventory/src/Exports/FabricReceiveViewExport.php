<?php

namespace SkylarkSoft\GoRMG\Inventory\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\Exportable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class FabricReceiveViewExport implements WithTitle, ShouldAutoSize, FromView, ShouldQueue
{
    use Exportable;

    private $receive;
    private $variableSettings;

    public function __construct($receive,$variableSettings)
    {
        $this->receive = $receive;
        $this->variableSettings = $variableSettings;
    }

    /**
     * @return string
     */
    public function title(): string
    {
        return 'Fabric Receive View';
    }

    /**
     * @return View
     */
    public function view(): View
    {
        $receive = $this->receive;
        $variableSettings = $this->variableSettings;
        return view('inventory::fabrics.pages.fabric_receive_view.excel',[
            'receive' => $receive,
            'variableSettings' => $variableSettings,
        ]);
    }
}
