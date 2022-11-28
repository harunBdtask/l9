<?php

namespace SkylarkSoft\GoRMG\Approval\Controllers;

use App\Http\Controllers\Controller;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use SkylarkSoft\GoRMG\Approval\Models\Approval;
use SkylarkSoft\GoRMG\Approval\Services\OrderApprovalService;
use SkylarkSoft\GoRMG\Approval\Services\PoApprovalUpdateService;
use SkylarkSoft\GoRMG\Merchandising\Models\Order;
use SkylarkSoft\GoRMG\Merchandising\Models\PurchaseOrder;
use Symfony\Component\HttpFoundation\Response;
use Throwable;

class OrderApprovalController extends Controller
{
    private const PAGE_NAME = 'Order Approval';

    public function index()
    {
        return view('approval::approvals.modules.order-approval');
    }

    public function search(Request $request)
    {
        $buyer_id = $request->get('buyer_id');

        return OrderApprovalService::for(self::PAGE_NAME)
            ->setRequest($request)
            ->setBuyer($buyer_id)
            ->response();

    }

    public function store(Request $request): JsonResponse
    {
        try {
            $order = OrderApprovalService::for(self::PAGE_NAME)
                ->setRequest($request)
                ->setBuyer($request->get('buyer_id'))
                ->store();

            return response()->json([
                'data' => $order,
                'status' => Response::HTTP_CREATED,
                'message' => 'Successfully Approval Updated',
            ], Response::HTTP_CREATED);
        } catch (Exception $exception) {
            return response()->json([
                'message' => $exception->getMessage(),
                'line' => $exception->getLine(),
                'file' => $exception->getFile(),
                'status' => Response::HTTP_INTERNAL_SERVER_ERROR
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function requestRemoveApprovals(Request $request)
    {
        return OrderApprovalService::for(self::PAGE_NAME)
            ->setRequest($request)
            ->setBuyer($request->get('buyer_id'))
            ->getUnapprovedData();
    }

    /**
     * @param Request $request
     * @return JsonResponse
     * @throws Throwable
     */
    public function updateRequestRemoveApprovals(Request $request): JsonResponse
    {
        try {
            DB::beginTransaction();

            Order::query()
                ->whereIn('id', $request->get('orders'))
                ->update([
                    'is_approve' => 0,
                    'step' => 0,
                    'un_approve_request' => null,
                    'approve_date' => null,
                ]);

            $allPONo = PurchaseOrder::query()
                ->whereIn('order_id', $request->get('orders'))
                ->get(['po_no']);

            (new PoApprovalUpdateService())
                ->unapprovePurchaseOrders(Approval::ORDER_APPROVAL, $allPONo);

            DB::commit();
            return response()->json([
                'message' => 'Successfully Updated!',
                'status' => Response::HTTP_OK
            ], Response::HTTP_OK);
        } catch (Exception $exception) {
            return response()->json(['message' => 'Something Went Wrong!'], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function orderApprovalStatus($id)
    {
        $order = Order::findOrFail($id);

        $approvals = Approval::query()
            ->where([
                'factory_id' => factoryId(),
                'page_name' => self::PAGE_NAME])
            ->whereRaw('FIND_IN_SET(?,buyer_ids)', [$order->buyer_id])
            ->exists();

        if ($approvals) {
            $lastStep = OrderApprovalService::for(self::PAGE_NAME)
                ->setBuyer($order->buyer_id)
                ->lastStep();
        } else {
            $lastStep = -1;
        }

        return response([
            'isApproved' => $order->step === $lastStep,
            'isReworkAble' => $order->rework_status,
        ]);
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
            $query = Order::query()->whereIn('job_no', $request->get('job_no'));
            if ($type == 'cancel') {
                $query->update(['cancel_status' => 1]);
            }
            if ($type == 'rework') {
                $query->each(function ($order) {
                    $order->update(['rework_status' => !$order->rework_status]);
                });
            }
            DB::commit();

            return response()->json('Success', Response::HTTP_OK);
        } catch (\Exception $exception) {
            return response()->json($exception->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function storePcd(Request $request): JsonResponse
    {
        try {
            Order::query()
                ->find($request->get('id'))
                ->update([
                    'pcd_date' => $request->get('pcd_date'),
                    'pcd_remarks' => $request->get('pcd_remarks'),
                    'ie_remarks' => $request->get('ie_remarks'),
                ]);
            return response()->json('Success', Response::HTTP_OK);
        } catch (\Exception $e) {
            return response()->json($e->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
