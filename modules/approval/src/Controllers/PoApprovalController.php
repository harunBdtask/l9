<?php


namespace SkylarkSoft\GoRMG\Approval\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use SkylarkSoft\GoRMG\Approval\Models\Approval;
use SkylarkSoft\GoRMG\Approval\Services\PoApprovalUpdateService;
use SkylarkSoft\GoRMG\Merchandising\Models\PurchaseOrder;
use Throwable;

class PoApprovalController extends controller
{
    public function index()
    {
        return view('approval::approvals.modules.poApproval');
    }

    /**
     * @throws Throwable
     */
    public function store(Request $request): JsonResponse
    {
        try {
            DB::beginTransaction();
            $type = $request->get('type');
            $poNo = $request->get('poNo');

            (new PoApprovalUpdateService())->approvePurchaseOrders(Approval::PO_APPROVAL, $poNo, $type);

            DB::commit();

            $response = [
                'status' => Response::HTTP_OK,
                'message' => 'po updated successfully',
            ];
            return response()->json($response, Response::HTTP_CREATED);
        } catch (\Exception $exception) {
            return response()->json($exception->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }


    public function getUnapprovedData(): JsonResponse
    {
        try {
            $unApprovedData = PurchaseOrder::query()
                ->where('is_approved', 1)
                ->whereNotNull('un_approve_request')
                ->with('buyer:id,name', 'createdBy:id,first_name,last_name', 'order:id,job_no,style_name')
                ->get()
                ->map(function ($item) {
                    return [
                        'id' => $item->id,
                        'job_no' => $item->order->job_no,
                        'style' => $item->order->style_name,
                        'po_no' => $item->po_no,
                        'buyer' => $item->buyer->name,
                        'user' => $item->createdBy->first_name . ' ' . $item->createdBy->last_name,
                        'unapproved_request' => $item->un_approve_request,
                    ];
                });

            return response()->json($unApprovedData, Response::HTTP_OK);
        } catch (\Exception $exception) {
            return response()->json($exception->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * @throws Throwable
     */
    public function updateApprovedStatus(Request $request): JsonResponse
    {
        try {
            DB::beginTransaction();
            $poNo = $request->get('po_no');

            (new PoApprovalUpdateService())->unapprovePurchaseOrders(Approval::PO_APPROVAL, $poNo);

            DB::commit();

            return response()->json(['message' => 'Successfully Updated!'], Response::HTTP_CREATED);
        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage()], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
