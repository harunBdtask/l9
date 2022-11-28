<?php

namespace SkylarkSoft\GoRMG\Merchandising\Controllers\Orders;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Notification;
use SkylarkSoft\GoRMG\Approval\Models\Approval;
use SkylarkSoft\GoRMG\Merchandising\Models\PurchaseOrder;
use SkylarkSoft\GoRMG\Merchandising\Notifications\POUnApproveRequestNotification;
use SkylarkSoft\GoRMG\Merchandising\Services\Approval\ApprovalPermittedUserService;
use SkylarkSoft\GoRMG\SystemSettings\Models\User;
use Symfony\Component\HttpFoundation\Response;

class OrderPoApprovalController extends Controller
{
    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function poReadyToApprove(Request $request): JsonResponse
    {
        try {
            $purchaseOrder = PurchaseOrder::query()->findOrFail($request->get('id'));
            $purchaseOrder->ready_to_approved = $purchaseOrder->ready_to_approved == 0 ? 1 : 0;
            $purchaseOrder->save();
            return response()->json($purchaseOrder, Response::HTTP_OK);
        } catch (\Exception $exception) {
            return response()->json($exception->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function poReadyToApproveAll(Request $request)
    {
        // true for unapprove and false for approve(1)
        try {
            if ($request->get('approve_status')) {
                $purchaseOrder = PurchaseOrder::query()->whereIn('id', $request->get('id'))->update(['ready_to_approved' => 0]);
            } else {
                $purchaseOrder = PurchaseOrder::query()->whereIn('id', $request->get('id'))->update(['ready_to_approved' => 1]);
            }
            return response()->json($purchaseOrder, Response::HTTP_OK);
        } catch (\Exception $exception) {
            return response()->json($exception->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
        }

    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function poUnApproveRequest(Request $request): JsonResponse
    {
        try {
            $purchaseOrder = PurchaseOrder::query()->findOrFail($request->get('id'));
            $purchaseOrder->update([
                'un_approve_request' => $request->get('un_approve_request')
            ]);
            return response()->json($purchaseOrder, Response::HTTP_OK);
        } catch (\Exception $exception) {
            return response()->json($exception->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function sendUnApprovedRequest(PurchaseOrder $purchaseOrder): JsonResponse
    {
        $purchaseOrder->load('order');
        $approvalPermittedUser = ApprovalPermittedUserService::for('PO Approval')
            ->setApprovalType('unapprove')
            ->setBuyer($purchaseOrder->buyer_id)
            ->setStep(0)
            ->get();
        $purchaseOrder->update(['un_approve_request' => 'Requested for PO approve.']);
        Notification::send($approvalPermittedUser, new POUnApproveRequestNotification([
                'po_no' => $purchaseOrder->po_no,
                'buyer_id' => $purchaseOrder->buyer_id,
                'job_no' => $purchaseOrder->order->job_no,
                'factory_id' => $purchaseOrder->factory_id,
            ])
        );

        return response()->json([
            'msg' => 'Request Sent Successfully'
        ], Response::HTTP_OK);
    }
}
