<?php

namespace SkylarkSoft\GoRMG\Approval\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use SkylarkSoft\GoRMG\Approval\Models\Approval;
use SkylarkSoft\GoRMG\Approval\Services\TrimsBookingApprovalService;
use SkylarkSoft\GoRMG\Approval\Services\YarnPurchaseOrderApprovalService;
use SkylarkSoft\GoRMG\Merchandising\Models\YarnPurchaseOrder;
use Symfony\Component\HttpFoundation\Response;
use Throwable;

class YarnPurchaseApprovalController extends Controller
{
    const PAGE_NAME = 'Yarn Purchase Approval';

    public function index()
    {
        return view('approval::approvals.modules.yarn-purchase');
    }

    public function create(Request $request)
    {
        try {

            $bookings = YarnPurchaseOrderApprovalService::for(Approval::YARN_PURCHASE_APPROVAL)
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
            $service = YarnPurchaseOrderApprovalService::for(Approval::YARN_PURCHASE_APPROVAL)
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
        $bookings = YarnPurchaseOrderApprovalService::for(Approval::YARN_PURCHASE_APPROVAL)
            ->setRequest($request)
            ->setBuyer($request->get('buyer'))
            ->getUnapprovedData();


        return response()->json($bookings);
    }

    public function unApprove(Request $request): JsonResponse
    {
        try {
            $quotations = YarnPurchaseOrder::query()->whereIn('id', $request->submitId)->update([
                'is_approved' => 0,
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
            $query = YarnPurchaseOrder::query()
                ->whereIn('id', $request->get('booking_ids'));
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
        $yarnPurchaseOrder = YarnPurchaseOrder::findOrFail($id);
        $lastStep = YarnPurchaseOrderApprovalService::for(self::PAGE_NAME)
            ->setBuyer($yarnPurchaseOrder->buyer_id)
            ->lastStep();

        return response([
            'isApproved' => $yarnPurchaseOrder->step === $lastStep,
            'isReworkAble' => $yarnPurchaseOrder->rework_status,
        ]);
    }
}
