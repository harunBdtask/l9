<?php

namespace SkylarkSoft\GoRMG\HR\Controllers\Api;

use App\Http\Controllers\Controller;
use SkylarkSoft\GoRMG\HR\ApiResponse;
use SkylarkSoft\GoRMG\HR\Repositories\DepartmentRepository;
use SkylarkSoft\GoRMG\HR\Requests\DepartmentRequest;
use SkylarkSoft\GoRMG\HR\Resources\DepartmentResource;
use Illuminate\Support\Facades\Session;
use Request;

class DepartmentController extends Controller
{
    public function index()
    {
        $departmentRepo = new DepartmentRepository();
        $departments = $departmentRepo->paginate();

        return view('hr::departments.index', [
            'departments' => $departments, 
            'department' => null
        ]);
    }

    public function departmentsList()
    {
        $departmentRepo = new DepartmentRepository();
        $apiResponse = new ApiResponse($departmentRepo->all(), DepartmentResource::class);
        return $apiResponse->getResponse();
    }

    public function store(DepartmentRequest $request)
    {
        $departmentRepo = (new DepartmentRepository())->store($request);
        Session::flash('success', 'Data Created successfully');
        
        return redirect()->back();
    }

    public function show($id)
    {
        $departmentRepo = (new DepartmentRepository())->show($id);
        
        return response()->json($departmentRepo, 200);
    }

    public function edit($id) {
        $departmentRepo = new DepartmentRepository();
        $departments = $departmentRepo->paginate();  
        $department = $departmentRepo->show($id);

        return view('hr::departments.index', [
            'departments' => $departments, 
            'department' => $department
        ]);
    }
    
    public function update($id, DepartmentRequest $request)
    {
        $departmentRepo = (new DepartmentRepository())->update($request);
        
        Session::flash('success', 'Data Updated successfully');
        return redirect('hr/departments');
    }

    public function destroy($id)
    {
        $departmentRepo = (new DepartmentRepository())->destroy($id);
        
        Session::flash('success', 'Data Deleted successfully');
        return redirect()->back();
    }
}
