<?php

namespace SkylarkSoft\GoRMG\Approval\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use PhpOffice\PhpSpreadsheet\Calculation\Web\Service;
use SkylarkSoft\GoRMG\Approval\Models\Approval;
use SkylarkSoft\GoRMG\Approval\Services\EmbellishmentApprovalService;
use SkylarkSoft\GoRMG\Approval\Services\ServiceBookingApprovalService;
use SkylarkSoft\GoRMG\Approval\Services\TrimsBookingApprovalService;
use SkylarkSoft\GoRMG\Merchandising\Models\Bookings\FabricBooking;
use SkylarkSoft\GoRMG\Merchandising\Models\Bookings\FabricServiceBooking;
use SkylarkSoft\GoRMG\Merchandising\Models\WorkOrders\EmbellishmentWorkOrder;
use Symfony\Component\HttpFoundation\Response;
use Throwable;

class EmbellishmentApprovalController extends Controller
{
    const PAGE_NAME = 'Embellishment Approval';

    public function index()
    {
        return view('approval::approvals.modules.embellishment');
    }

    public function create(Request $request)
    {
        try {

            $bookings = EmbellishmentApprovalService::for(Approval::EMBELLISHMENT_APPROVAL)
                ->setRequest($request)
                ->setBuyer($request->get('buyer'))
                ->response();

            $response = [
                'data' => $bookings,
                'status' => Response::HTTP_OK,
                'message' => 'Trims booking fetched successfully',
            ];

            $response = [
                'data' => $bookings,
                'status' => Response::HTTP_OK,
                'message' => 'Fabric booking fetched successfully',
            ];

            return response()->json($response, Response::HTTP_OK);
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
            $service = EmbellishmentApprovalService::for(Approval::EMBELLISHMENT_APPROVAL)
                ->setRequest($request)
                ->setBuyer($request->get('buyer'))
                ->store();

            $response = [
                'data' => $service,
                'status' => Response::HTTP_OK,
                'message' => 'Updated Successfully!',
            ];
            DB::commit();

            return response()->json($response, Response::HTTP_OK);
        } catch (\Exception $exception) {
            return response()->json($exception->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function unApprovedRequests(Request $request): JsonResponse
    {
        $bookings = EmbellishmentApprovalService::for(Approval::EMBELLISHMENT_APPROVAL)
            ->setRequest($request)
            ->setBuyer($request->get('buyer'))
            ->response();

        return response()->json($bookings);
    }

    public function unApprove(Request $request): JsonResponse
    {
        try {
            $quotations = EmbellishmentWorkOrder::query()->whereIn('id', $request->submitId)->update([
                'is_approved' => 0,
                'step' => 0
            ]);

            return response()->json(['message' => 'Successfully Updated!'], Response::HTTP_CREATED);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Something Went Wrong!'], Response::HTTP_BAD_REQUEST);
        }
    }
}
