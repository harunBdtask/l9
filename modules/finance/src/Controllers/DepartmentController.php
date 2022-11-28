<?php

namespace SkylarkSoft\GoRMG\Finance\Controllers;

use Exception;
use App\Http\Controllers\Controller;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Routing\Redirector;
use Illuminate\Support\Facades\Session;
use SkylarkSoft\GoRMG\Finance\Models\Department;
use SkylarkSoft\GoRMG\Finance\Requests\DepartmentFormRequest;

class DepartmentController extends Controller
{

    /**
     * @return Application|Factory|View
     */
    public function index()
    {
        $departments = Department::query()->orderByDesc('id')->paginate();

        return view('finance::pages.departments', [
            'departments' => $departments
        ]);
    }

    /**
     * @return Application|Factory|View
     */
    public function create()
    {
        $data['department'] = null;

        return view('finance::forms.department', $data);
    }

    /**
     * @param DepartmentFormRequest $request
     * @param Department $department
     * @return Application|RedirectResponse|Redirector
     */
    public function store(DepartmentFormRequest $request, Department $department)
    {
        try {
            $department->fill($request->all())->save();
            Session::flash('alert-success', 'Data Stored Successfully!!');

            return redirect('finance/departments');
        } catch (Exception $exception) {
            Session::flash('alert-danger', "Something went wrong! {$exception->getMessage()}");

            return redirect()->back();
        }
    }

    /**
     * @param $department
     * @return Application|Factory|View
     */
    public function edit($department)
    {
        $data['department'] = Department::query()
            ->where('id', $department)
            ->first();

        return view('finance::forms.department', $data);
    }

    /**
     * @param DepartmentFormRequest $request
     * @param Department $department
     * @return Application|RedirectResponse|Redirector
     */
    public function update(DepartmentFormRequest $request, Department $department)
    {
        try {
            $department->fill($request->all())->save();
            Session::flash('alert-success', 'Data Update Successfully!!');

            return redirect('finance/departments');
        } catch (Exception $exception) {
            Session::flash('alert-danger', "Something went wrong! {$exception->getMessage()}");

            return redirect()->back();
        }
    }

    /**
     * @param Department $department
     * @return Application|RedirectResponse|Redirector
     */
    public function destroy(Department $department)
    {
        try {
            $department->delete();
            Session::flash('alert-success', 'Data Deleted Successfully!!');

            return redirect('finance/departments');
        } catch (Exception $exception) {
            Session::flash('alert-danger', "Something went wrong!! {$exception->getMessage()}");

            return redirect()->back();
        }
    }

}
