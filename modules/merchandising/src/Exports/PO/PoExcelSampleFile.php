<?php


namespace SkylarkSoft\GoRMG\Merchandising\Exports\PO;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class PoExcelSampleFile implements FromView, ShouldAutoSize
{
    public function view(): View
    {
        return view('merchandising::po-files-excel.sample-file');
    }
}
