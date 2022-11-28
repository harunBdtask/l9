<?php

namespace SkylarkSoft\GoRMG\ManualProduction\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class FloorSizeWiseStyleInoutExport implements FromView, ShouldAutoSize
{
    use Exportable;

    protected $sizes, $reports;

    public function __construct($reports, $sizes)
    {
        $this->sizes = $sizes;
        $this->reports = $reports;
    }

    public function view(): View
    {
        $sizes = $this->sizes;
        $reports = $this->reports;
        return view('manual-production::reports.sewing.includes.floor_size_wise_style_in_out_summary_include',
            compact('sizes', 'reports'));
    }
}
