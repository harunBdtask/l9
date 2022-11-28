<?php

namespace SkylarkSoft\GoRMG\Approval\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use SkylarkSoft\GoRMG\Approval\Models\Approval;
use SkylarkSoft\GoRMG\Approval\Services\ShortFabricBookingApprovalService;
use SkylarkSoft\GoRMG\Merchandising\Models\ShortBookings\ShortFabricBooking;
use Symfony\Component\HttpFoundation\Response;
use Throwable;

class ShortFabricBookingApprovalController extends Controller
{
    const PAGE_NAME =  'Short Fabric Approval';
    public function index()
    {
        return view('approval::approvals.modules.short-fabric-booking');
    }

    public function create(Request $request)
    {
        try {
            $bookings = ShortFabricBookingApprovalService::for(Approval::SHORT_FABRIC_APPROVAL)
                ->setRequest($request)
                ->setBuyer($request->get('buyer'))
                ->response();

            $response = [
                'data'    => $bookings,
                'status'  => Response::HTTP_OK,
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
            $fabric = ShortFabricBookingApprovalService::for(Approval::SHORT_FABRIC_APPROVAL)
                ->setRequest($request)
                ->setBuyer($request->get('buyer'))
                ->store();

            $response = [
                'data' => $fabric,
                'status'  => Response::HTTP_OK,
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
        $bookings = ShortFabricBookingApprovalService::for(Approval::SHORT_FABRIC_APPROVAL)
            ->setRequest($request)
            ->setBuyer($request->get('buyer'))
            ->getUnapprovedData();

        return response()->json($bookings);
    }

    public function unApprove(Request $request): JsonResponse
    {
        try {
            $quotations = ShortFabricBooking::query()->whereIn('id', $request->submitId)->update([
                'is_approved' => null,
                'step' => 0
            ]);

            return response()->json(['message' => 'Successfully Updated!'], Response::HTTP_CREATED);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Something Went Wrong!'], Response::HTTP_BAD_REQUEST);
        }
    }
}
