<?php

namespace SkylarkSoft\GoRMG\Merchandising\Controllers\Orders;

use Exception;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Notification;
use SkylarkSoft\GoRMG\Merchandising\Models\PurchaseOrder;
use SkylarkSoft\GoRMG\SystemSettings\Models\User;
use Symfony\Component\HttpFoundation\Response;
use SkylarkSoft\GoRMG\Merchandising\Notifications\PoSendApprovalNotification;
use SkylarkSoft\GoRMG\Approval\Models\Approval;

class OrderAllPoApprovalController extends Controller
{
    public function allPoApprove(Request $request)
    {
        try {

            DB::beginTransaction();
            $orderId = $request->get('order_id');
            $purchaseOrder = PurchaseOrder::query()
                ->with(['order'])
                ->where('order_id', $orderId)
                ->where('ready_to_approved',0)
                ->get();

            foreach ($purchaseOrder as $key => $poApprove) {
                $poApprove->update([
                    'ready_to_approved' => 1
                ]);
                $this->poApproveNotifyUser($poApprove);
            }
            DB::commit();

            return response()->json([
                'status' => 'Success',
                'message' => 'Data Saved Successfully'
            ], Response::HTTP_OK);

        } catch (Exception $exception) {
            return response()->json($exception->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function poApproveNotifyUser($poApprove)
    {
        $poApprovalUser = Approval::query()->where('page_name','PO Approval')
            ->pluck('user_id')
            ->unique()->values();

        $users = User::query()->whereIn('id', $poApprovalUser)->get();
        Notification::send($users, new PoSendApprovalNotification([
                'id' => $poApprove->id,
                'name' => $poApprove->order->style_name,
                'po_no' => $poApprove->po_no,
                'order_no' => $poApprove->order->job_no,
                'factory_id' => $poApprove->factory_id,
                'buyer_id' => $poApprove->buyer_id,
            ])
        );
    }

}
