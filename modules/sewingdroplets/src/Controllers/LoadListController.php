<?php

namespace SkylarkSoft\GoRMG\Sewingdroplets\Controllers;

use App\Http\Controllers\Controller;
use Carbon\Carbon;
use DateInterval;
use Illuminate\Http\Request;
use DB, Session, Exception;
use SkylarkSoft\GoRMG\Merchandising\Models\PoColorSizeBreakdown;
use SkylarkSoft\GoRMG\SystemSettings\Models\Buyer;
use SkylarkSoft\GoRMG\Merchandising\Models\PurchaseOrder;
use SkylarkSoft\GoRMG\Sewingdroplets\Models\SewingPlan;
use SkylarkSoft\GoRMG\Sewingdroplets\Models\SewingPlanDetail;

class LoadListController extends Controller
{
    public function getLoadListModalContent()
    {
        $buyers = Buyer::pluck('name', 'id');
        $html = view('sewingdroplets::pages.load_list_modal_content', [
            'buyers' => $buyers
        ])->render();
        return response()->json([
            'html' => $html
        ]);
    }

    public function generateLoadList(Request $request)
    {
        $validator = \Validator::make($request->all(), [
            'buyer_id' => 'required',
            'order_id' => 'required',
            'garments_item_id' => 'required',
        ], [
            'required' => 'This field is required',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'errors' => $validator->errors()
            ]);
        }

        try {
            $buyer_id = $request->buyer_id;
            $order_id = $request->order_id;
            $garments_item_id = $request->garments_item_id;
            $po_item_details = PoColorSizeBreakdown::query()
                ->where([
                    'order_id' => $order_id,
                    'garments_item_id' => $garments_item_id,
                ])->get()
                ->filter(function ($item, $key) {
                    $plan_qty = SewingPlanDetail::query()
                        ->where([
                            'garments_item_id' => $item->garments_item_id,
                            'purchase_order_id' => $item->id,
                        ])
                        ->sum('allocated_qty');
                    return $item->quantity > $plan_qty;
                });
            $html = view('sewingdroplets::pages.load_list_table', [
                'po_item_details' => $po_item_details,
                'garments_item_id' => $garments_item_id,
            ])->render();

            return response()->json([
                'status' => 'success',
                'errors' => null,
                'html' => $html,
            ]);
        } catch (Exception $e) {
            $html = view('partials.flash_message', [
                'message_class' => "danger",
                'message' => "Something went wrong!!"
            ])->render();

            return response()->json([
                'status' => 'danger',
                'errors' => $e->getMessage(),
                'message' => $html
            ]);
        }
    }
}
