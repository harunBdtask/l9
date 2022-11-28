<?php

namespace SkylarkSoft\GoRMG\Approval\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use SkylarkSoft\GoRMG\Approval\Models\Approval;
use SkylarkSoft\GoRMG\Approval\Services\BudgetApprovalService;
use SkylarkSoft\GoRMG\Approval\Services\FabricBookingApprovalService;
use SkylarkSoft\GoRMG\Approval\Services\GatePassChallanApprovalService;
use SkylarkSoft\GoRMG\Approval\Services\TrimsBookingApprovalService;
use SkylarkSoft\GoRMG\Merchandising\Models\Bookings\FabricBooking;
use SkylarkSoft\GoRMG\Merchandising\Models\GatePassChallan\GatePasChallan;
use Symfony\Component\HttpFoundation\Response;
use Throwable;

class GatePassChallanApprovalController extends Controller
{
    const PAGE_NAME =  'Gate Pass Challan Approval';
    public function index()
    {
        return view('approval::approvals.modules.gatePassChallan');
    }

    public function create(Request $request): JsonResponse
    {
        try {
            $data = GatePassChallanApprovalService::for(Approval::GATE_PASS_CHALLAN_APPROVAL)
                ->setRequest($request)
                ->response();

            return response()->json($data, Response::HTTP_OK);
        } catch (\Exception $exception) {
            return response()->json($exception->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * @throws Throwable
     */
    public function store(Request $request): JsonResponse
    {
        try {
            DB::beginTransaction();
            $data = GatePassChallanApprovalService::for(Approval::GATE_PASS_CHALLAN_APPROVAL)
                ->setRequest($request)
                ->store();
            DB::commit();

            return response()->json($data, Response::HTTP_OK);
        } catch (\Exception $exception) {
            return response()->json($exception->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function unApprovedRequests(Request $request): JsonResponse
    {
        $bookings = GatePassChallanApprovalService::for(Approval::GATE_PASS_CHALLAN_APPROVAL)
            ->setRequest($request)
            ->getUnapprovedData();

        return response()->json($bookings);
    }

    public function unApprove(Request $request): JsonResponse
    {
        try {
            GatePasChallan::query()->whereIn('id', $request->get('submitId'))->update([
                'is_approve' => null,
                'step' => 0,
                'approved_by' => '[]',
            ]);

            return response()->json(['message' => 'Successfully Updated!'], Response::HTTP_OK);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Something Went Wrong!'], Response::HTTP_BAD_REQUEST);
        }
    }
}
