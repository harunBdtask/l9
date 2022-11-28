<?php

namespace SkylarkSoft\GoRMG\HR\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use SkylarkSoft\GoRMG\HR\ApiResponse;
use SkylarkSoft\GoRMG\HR\Repositories\DepartmentRepository;
use SkylarkSoft\GoRMG\HR\Repositories\SectionRepository;
use SkylarkSoft\GoRMG\HR\Requests\SectionRequest;
use SkylarkSoft\GoRMG\HR\Resources\SectionResource;

class SectionController extends Controller
{
    public function index(Request $request)
    {
        $sectionRepo = new SectionRepository();
        $sections = $sectionRepo->paginate();
        $departments = (new DepartmentRepository())->all();

        $arrangeDepartment = array();
        foreach($departments as $key => $value) {
            $arrangeDepartment[$value->id] = $value->name;
        }

        return view('hr::sections.index', [
            'sections' => $sections,
            'departments' => $arrangeDepartment,
            'section' => null
        ]);
    }

    public function sectionsList(Request $request)
    {
        $sectionRepo = new SectionRepository();
        $apiResponse = new ApiResponse($sectionRepo->all($request->department_id), SectionResource::class);

        return $apiResponse->getResponse();
    }

    public function store(SectionRequest $request)
    {
        $sectionRepo = (new SectionRepository())->store($request);
        Session::flash('success', 'Data Created successfully');

        return redirect()->back();
    }

    public function show($id)
    {
        $sectionRepo = (new SectionRepository())->show($id);

        return response()->json($sectionRepo, 200);
    }

    public function edit($id) {
        $sectionRepo = new SectionRepository();
        $sections = $sectionRepo->paginate();
        $departments = (new DepartmentRepository())->all();
        $section = $sectionRepo->show($id);

        $arrangeDepartment = array();
        foreach($departments as $key => $value) {
            $arrangeDepartment[$value->id] = $value->name;
        }

        return view('hr::sections.index', [
            'sections' => $sections,
            'departments' => $arrangeDepartment,
            'section' => $section
        ]);
    }

    public function update($id, SectionRequest $request)
    {
        $sectionRepo = (new SectionRepository())->update($request);

        Session::flash('success', 'Data Updated successfully');
        return redirect('hr/sections');
    }

    public function destroy($id)
    {
        $sectionRepo = (new SectionRepository())->destroy($id);

        Session::flash('success', 'Data Deleted successfully');
        return redirect()->back();
    }
}
