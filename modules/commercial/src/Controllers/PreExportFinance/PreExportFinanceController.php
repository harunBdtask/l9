<?php

namespace SkylarkSoft\GoRMG\Commercial\Controllers\PreExportFinance;

use App\Http\Controllers\Controller;

class PreExportFinanceController extends Controller
{
    public function index()
    {
        return view('commercial::pre-export-finance.create_update');
    }
}
