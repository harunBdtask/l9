<?php

namespace SkylarkSoft\GoRMG\Approval\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use SkylarkSoft\GoRMG\Approval\Models\Approval;
use SkylarkSoft\GoRMG\Approval\Services\FabricBookingApprovalService;
use SkylarkSoft\GoRMG\Merchandising\Models\Bookings\FabricBooking;
use SkylarkSoft\GoRMG\Merchandising\Models\Bookings\FabricServiceBooking;
use Symfony\Component\HttpFoundation\Response;
use Throwable;

class FabricBookingApprovalController extends Controller
{
    const PAGE_NAME = 'Fabric Approval';

    public function index()
    {
        return view('approval::approvals.modules.fabric-booking');
    }

    public function create(Request $request): JsonResponse
    {
        try {
            $bookings = FabricBookingApprovalService::for(Approval::FABRIC_APPROVAL)
                ->setRequest($request)
                ->setBuyer($request->get('buyer'))
                ->response();

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
            $fabric = FabricBookingApprovalService::for(Approval::FABRIC_APPROVAL)
                ->setRequest($request)
                ->setBuyer($request->get('buyer'))
                ->store();

            $response = [
                'data' => $fabric,
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
        $bookings = FabricBookingApprovalService::for(Approval::FABRIC_APPROVAL)
            ->setRequest($request)
            ->setBuyer($request->get('buyer'))
            ->getUnapprovedData();

        return response()->json($bookings);
    }

    public function unApprove(Request $request): JsonResponse
    {
        try {
            $quotations = FabricBooking::query()->whereIn('id', $request->submitId)->update([
                'is_approve' => null,
                'step' => 0
            ]);

            return response()->json(['message' => 'Successfully Updated!'], Response::HTTP_CREATED);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Something Went Wrong!'], Response::HTTP_BAD_REQUEST);
        }
    }

    /**
     * @param Request $request
     * @return JsonResponse
     * @throws Throwable
     */
    public function cancelAndRework(Request $request): JsonResponse
    {
        try {
            DB::beginTransaction();
            $type = $request->get('type');
            $query = FabricBooking::query()->whereIn('id', $request->get('booking_ids'));
            if ($type == 'cancel') {
                $query->update(['cancel_status' => 1]);
            }
            if ($type == 'rework') {
                $query->each(function ($booking) {
                    $booking->update(['rework_status' => !$booking->rework_status]);
                });
            }
            DB::commit();

            return response()->json('Success', Response::HTTP_OK);
        } catch (\Exception $exception) {
            return response()->json($exception->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function checkApprovalStatus($id)
    {
        $booking = FabricBooking::findOrFail($id);
        $lastStep = FabricBookingApprovalService::for(self::PAGE_NAME)
            ->setBuyer($booking->buyer_id)
            ->lastStep();

        return response([
            'isApproved' => $booking->step === $lastStep,
            'isReworkAble' => $booking->rework_status,
        ]);
    }
}