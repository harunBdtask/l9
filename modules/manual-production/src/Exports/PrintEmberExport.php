<?php
namespace SkylarkSoft\GoRMG\ManualProduction\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use SkylarkSoft\GoRMG\ManualProduction\Models\Reports\ManualDateWisePrintEmbrReport;

class PrintEmberExport implements FromView, ShouldAutoSize
{
    protected $date_from, $date_to;

    public function __construct($date_from, $date_to)
    {
        $this->date_from = $date_from;
        $this->date_to = $date_to;
    }

    public function view(): View
    {
        $date_from = $this->date_from;
        $date_to = $this->date_to;
        $data = ManualDateWisePrintEmbrReport::query()
            ->with('factory', 'buyer', 'order', 'purchaseOrder', 'color')
            ->whereDate('production_date', '>=', $date_from)
            ->whereDate('production_date', '=', $date_to)
            ->get();

        return view('manual-production::reports.print_ember_report.master', compact('data', 'date_from', 'date_to'));
    }
}
