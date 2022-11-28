<?php

namespace SkylarkSoft\GoRMG\HR\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Exception;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Maatwebsite\Excel\Facades\Excel;
use SkylarkSoft\GoRMG\HR\Imports\AttendanceImport;

class AttendanceUploadController extends Controller
{
    public function attendanceListExcelUpload(Request $request)
    {

    }
}
