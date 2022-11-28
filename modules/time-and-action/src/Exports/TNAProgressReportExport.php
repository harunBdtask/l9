<?php

namespace SkylarkSoft\GoRMG\TimeAndAction\Exports;


use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\WithTitle;
use SkylarkSoft\GoRMG\TimeAndAction\PackageConst;

class TNAProgressReportExport implements WithTitle, FromView
{
    private $data;

    public function __construct($data)
    {
        $this->data = $data;
    }

    /**
     * @return View
     */
    public function view(): View
    {
        return view(PackageConst::VIEW . 'pdf.progress-report-body', $this->data);
    }

    /**
     * @return string
     */
    public function title(): string
    {
        return 'TNA Progress Report';
    }
}