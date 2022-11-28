<?php

namespace SkylarkSoft\GoRMG\HR\Controllers;

use App\Http\Controllers\Controller;
use Exception;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Maatwebsite\Excel\Facades\Excel;
use SkylarkSoft\GoRMG\HR\Imports\EmployeeImport;
use Throwable;

class EmployeeUploadController extends Controller
{
    public function employeeInformationExcelUploadForm()
    {
        return view('hr::employee.information_excel_upload_form');
    }

    /**
     * @param Request $request
     * @return RedirectResponse
     * @throws Throwable
     */
    public function employeeInformationExcelUpload(Request $request): RedirectResponse
    {
//        $request->validate([
//            'employee_excel' => 'required|mimes:csv,xls,xlsx'
//        ], [
//            'employee_excel.required' => 'File is required',
//            'employee_excel.mimes' => 'File must be of type csv or xls or xlsx',
//        ]);

        try {
            DB::beginTransaction();

            $excel = Excel::import(new EmployeeImport, $request->file('employee_excel'));

            if ($excel) {
                DB::commit();
                Session::flash('success', 'Success!! Data Stored Successfully!!');
            }

        } catch (Exception $exception) {
            Session::flash('error', "Oops! Something went wrong {$exception->getMessage()}");
        } finally {
            return redirect()->back();
        }


    }
}
