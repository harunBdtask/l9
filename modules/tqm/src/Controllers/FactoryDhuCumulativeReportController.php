<?php

namespace SkylarkSoft\GoRMG\TQM\Controllers;

use App\Http\Controllers\Controller;

class FactoryDhuCumulativeReportController extends Controller
{
    public function index()
    {
        return view('tqm::reports.factory-dhu-cumulative-report.index');
    }

    public function getReport()
    {
        return view('tqm::reports.factory-dhu-cumulative-report.table');
    }
}
