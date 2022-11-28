<?php

namespace SkylarkSoft\GoRMG\TQM\Controllers;

use App\Http\Controllers\Controller;

class FactoryDhuDailyReportController extends Controller
{
    public function index()
    {
        return view('tqm::reports.factory-dhu-daily-report.index');
    }

    public function getReport()
    {
        return view('tqm::reports.factory-dhu-daily-report.table');
    }
}
