<?php

namespace SkylarkSoft\GoRMG\SystemSettings\Controllers;

use App\Http\Controllers\Controller;

class ManagementDashboardController extends Controller
{
    public function index()
    {
        return view('system-settings::management-dashboard.index');
    }
}
