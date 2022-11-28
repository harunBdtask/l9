<?php

namespace SkylarkSoft\GoRMG\Approval\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use SkylarkSoft\GoRMG\Approval\Models\Approval;
use SkylarkSoft\GoRMG\Approval\Services\ShortTrimsBookingApprovalService;
use SkylarkSoft\GoRMG\Merchandising\Models\ShortBookings\ShortTrimsBooking;
use Symfony\Component\HttpFoundation\Response;
use Throwable;

class ShortTrimsBookingApprovalController extends Controller
{
    const PAGE_NAME = 'Short Trims Approval';

    public function index()
    {
        return view('approval::approvals.modules.short-trims-booking');
    }

    public function create(Request $request): JsonResponse
    {
        try {

            $bookings = ShortTrimsBookingApprovalService::for(Approval::SHORT_TRIMS_APPROVAL)
                ->setRequest($request)
                ->setBuyer($request->get('buyer'))
                ->response();

            $response = [
                'data' => $bookings,
                'status' => Response::HTTP_OK,
                'message' => 'Trims booking fetched successfully',
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
            $trims = ShortTrimsBookingApprovalService::for(Approval::SHORT_TRIMS_APPROVAL)
                ->setRequest($request)
                ->setBuyer($request->get('buyer'))
                ->store();

            $response = [
                'data' => $trims,
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
        $bookings = ShortTrimsBookingApprovalService::for(Approval::SHORT_TRIMS_APPROVAL)
            ->setRequest($request)
            ->setBuyer($request->get('buyer'))
            ->getUnapprovedData();

        return response()->json($bookings);
    }

    public function unApprove(Request $request): JsonResponse
    {
        try {
            $trims = ShortTrimsBooking::query()->whereIn('id', $request->submitId)->update([
                'is_approved' => null,
                'step' => 0
            ]);

            return response()->json(['message' => 'Successfully Updated!'], Response::HTTP_CREATED);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Something Went Wrong!'], Response::HTTP_BAD_REQUEST);
        }
    }
}
