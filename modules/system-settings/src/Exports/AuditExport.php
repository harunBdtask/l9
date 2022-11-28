<?php

namespace SkylarkSoft\GoRMG\SystemSettings\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use OwenIt\Auditing\Models\Audit;

class AuditExport implements FromView, ShouldAutoSize
{
    private $date;

    public function __construct($date)
    {
        $this->date = $date;
    }

    public function view(): View
    {
        $audits = Audit::query()
            ->whereDate('created_at', $this->date)
            ->latest()
            ->get();

        return view('system-settings::audit.data', compact('audits'));
    }
}
