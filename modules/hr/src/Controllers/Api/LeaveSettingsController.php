<?php

namespace SkylarkSoft\GoRMG\HR\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use SkylarkSoft\GoRMG\HR\ApiResponse;
use SkylarkSoft\GoRMG\HR\Models\HrLeaveSetting;
use SkylarkSoft\GoRMG\HR\Models\HrLeaveTypes;
use SkylarkSoft\GoRMG\HR\Repositories\LeaveSettingsRepository;
use SkylarkSoft\GoRMG\HR\Requests\LeaveSettingRequest;
use SkylarkSoft\GoRMG\HR\Resources\LeaveSettingResource;

class LeaveSettingsController extends Controller
{

    /**
     * @var LeaveSettingsRepository
     */
    private $repository;

    public function __construct(LeaveSettingsRepository $repository)
    {
        $this->repository = $repository;
    }

    public function index()
    {
        $employeeTypes = [
            [
                'id' => 'worker',
                'name' => 'Worker',
            ],
            [
                'id' => 'staff',
                'name' => 'Staff'
            ],
            [
                'id' => 'management',
                'name' => 'Management',
            ],
        ];

        $employeeTypes = collect($employeeTypes)->pluck('name', 'id');
        $leaveTypes = HrLeaveTypes::query()->pluck('name', 'id');
        $leaveSettings = $this->repository->paginate();

        return view('hr::leave-settings.index', [
            'employeeTypes' => $employeeTypes,
            'leaveTypes' => $leaveTypes,
            'leaveSettings' => $leaveSettings,
            'leaveSetting' => null
        ]);
    }

    public function store(LeaveSettingRequest $request)
    {
        $this->repository->store($request);
        Session::flash('success', 'Data Created successfully');

        return redirect()->back();
    }

    public function show($id)
    {
        return (new ApiResponse($this->repository->show($id), LeaveSettingResource::class))
            ->getResponse();
    }

    public function edit($id) {
        $employeeTypes = [
            [
                'id' => 'worker',
                'name' => 'Worker',
            ],
            [
                'id' => 'staff',
                'name' => 'Staff'
            ],
            [
                'id' => 'management',
                'name' => 'Management',
            ],
        ];

        $employeeTypes = collect($employeeTypes)->pluck('name', 'id');
        $leaveTypes = HrLeaveTypes::query()->pluck('name', 'id');
        $leaveSettings = $this->repository->paginate();
        $leaveSetting = $this->repository->show($id);

        return view('hr::leave-settings.index', [
            'employeeTypes' => $employeeTypes,
            'leaveTypes' => $leaveTypes,
            'leaveSettings' => $leaveSettings,
            'leaveSetting' => $leaveSetting
        ]);
    }


    public function update(LeaveSettingRequest $request, $id)
    {
        $this->repository->update($request, $id);

        Session::flash('success', 'Data Updated successfully');
        return redirect('hr/leave-settings');
    }

    public function destroy($id)
    {
        return (new ApiResponse($this->repository->destroy($id), LeaveSettingResource::class))
            ->getResponse();
    }

    public function getLeaveSettings()
    {
        try {
            return response()->json(HrLeaveSetting::all(), 200);
        } catch (\Exception $e) {
            return response()->json($e->getMessage(), 500);
        }
    }
}
