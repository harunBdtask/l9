<?php

namespace SkylarkSoft\GoRMG\TimeAndAction\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class TNAReportExport implements FromView, ShouldAutoSize
{
    private $data;
    private $variable;
    public function __construct($data, $variable)
    {
        $this->data = $data;
        $this->variable = $variable;
    }

    public function view(): View
    {
        $data = $this->data;
        $variable = $this->variable;
        return view('time-and-action::excel.report', compact('data', 'variable'));
    }
}
