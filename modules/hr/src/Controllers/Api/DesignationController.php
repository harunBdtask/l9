<?php

namespace SkylarkSoft\GoRMG\HR\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Session;
use SkylarkSoft\GoRMG\HR\ApiResponse;
use SkylarkSoft\GoRMG\HR\Models\HrDesignation;
use SkylarkSoft\GoRMG\HR\Repositories\DesignationRepository;
use SkylarkSoft\GoRMG\HR\Requests\DesignationRequest;
use SkylarkSoft\GoRMG\HR\Resources\DesignationResource;

class DesignationController extends Controller
{

    public function index()
    {
        $designationRepo = new DesignationRepository();
        $designations = $designationRepo->paginate();

        return view('hr::designations.index', [
            'designations' => $designations,
            'designation' => null
        ]);
    }

    public function designationsList()
    {
        try {
            $designations = HrDesignation::query()->get(['id', 'name as text']);
            return response()->json($designations, 200);
        } catch (\Exception $e) {
            return response()->json($e->getMessage(), 500);
        }
    }

    public function store(DesignationRequest $request)
    {
        $designationRepo = (new DesignationRepository())->store($request);
        Session::flash('success', 'Data Created successfully');
        
        return redirect()->back();
    }

    public function show($id)
    {
        $designationRepo = (new DesignationRepository())->show($id);
        
        return response()->json($designationRepo, 200);
    }

    public function edit($id) {
        $designationRepo = new DesignationRepository();
        $designations = $designationRepo->paginate();
        $designation = $designationRepo->show($id);

        return view('hr::designations.index', [
            'designations' => $designations,
            'designation' => $designation
        ]);
    }

    public function update($id, DesignationRequest $request)
    {
        $designationRepo = (new DesignationRepository())->update($request);

        Session::flash('success', 'Data Updated successfully');
        return redirect('hr/designations');
    }

    public function destroy($id)
    {
        $designationRepo = new DesignationRepository();
        $apiResponse = new ApiResponse($designationRepo->destroy($id), DesignationResource::class);

        return $apiResponse->getResponse();
    }
}
