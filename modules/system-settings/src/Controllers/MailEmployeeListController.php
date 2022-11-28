<?php

namespace SkylarkSoft\GoRMG\SystemSettings\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Session;
use SkylarkSoft\GoRMG\SystemSettings\Models\MailEmployeeList;
use SkylarkSoft\GoRMG\SystemSettings\Requests\MailEmployeeListRequest;

class MailEmployeeListController extends Controller
{
    public function index()
    {
        $data['mail_employee_lists'] = MailEmployeeList::paginate();

        return view('system-settings::mail-employee-list.list', $data);
    }

    public function store(MailEmployeeListRequest $request)
    {
        try {
            $id = $request->id ?? null;
            $MailEmployeeList = MailEmployeeList::findOrNew($id);
            $MailEmployeeList->email = $request->get('email');
            $MailEmployeeList->status = $request->get('status');
            $MailEmployeeList->save();
            if ($request->id) {
                Session::flash('alert-success', 'Data Updated Successfully');
            } else {
                Session::flash('alert-success', 'Data Stored Successfully');
            }

            return redirect('mail-employee-list');
        } catch (\Exception $exception) {
            Session::flash('alert-danger', 'Something Went Wrong');

            return redirect('mail-employee-list');
        }
    }

    public function edit($id)
    {
        return MailEmployeeList::findOrFail($id);
    }

    public function delete($id)
    {
        $mail_employee_list = MailEmployeeList::findOrFail($id)->delete();
        if ($mail_employee_list) {
            Session::flash('alert-danger', 'Data Deleted Successfully!!');

            return redirect()->back();
        }
        Session::flash('alert-danger', 'Data Delete Failed!!');

        return redirect()->back();
    }
}
