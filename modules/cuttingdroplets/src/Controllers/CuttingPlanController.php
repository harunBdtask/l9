<?php

namespace SkylarkSoft\GoRMG\Cuttingdroplets\Controllers;

use App\Http\Controllers\Controller;
use Carbon\Carbon;
use DateInterval;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Exception;
use SkylarkSoft\GoRMG\Cuttingdroplets\Models\BundleCard;
use SkylarkSoft\GoRMG\Cuttingdroplets\Models\CuttingPlan;
use SkylarkSoft\GoRMG\Merchandising\Models\Order;
use SkylarkSoft\GoRMG\Merchandising\Models\PurchaseOrder;
use SkylarkSoft\GoRMG\Merchandising\Models\PurchaseOrderDetail;
use SkylarkSoft\GoRMG\SystemSettings\Models\Buyer;
use SkylarkSoft\GoRMG\SystemSettings\Models\CuttingFloor;
use SkylarkSoft\GoRMG\SystemSettings\Models\CuttingTable;
use SkylarkSoft\GoRMG\SystemSettings\Models\Factory;

class CuttingPlanController extends Controller
{
    public function cuttingPlan()
    {
        $date = date('Y-m-d');
        $cutting_floors = [];
        if (getRole() == 'super-admin' || getRole() == 'admin') {
            $factories = Factory::pluck('factory_name', 'id');
        } else {
            $factories = Factory::where('id', factoryId())->pluck('factory_name', 'id');
        }
        return view('cuttingdroplets::pages.cutting_plan', [
            'factories' => $factories,
            'cutting_floors' => $cutting_floors,
            'date' => $date,
        ]);
    }

    public function getCuttingFloorsForFactory($factory_id)
    {
        return CuttingFloor::withoutGlobalScopes()->where('factory_id', $factory_id)
            ->whereNull('deleted_at')
            ->pluck('floor_no', 'id');
    }

    public function getBuyersForDropdown($factory_id)
    {
        return Buyer::withoutGlobalScopes()
            ->whereNull('deleted_at')
            ->where('factory_id', $factory_id)->pluck('name', 'id');
    }

    public function getOrderList($buyer_id)
    {
        $orders_query = Order::withoutGlobalScopes()->whereNull('deleted_at')->where('buyer_id', $buyer_id);
        $response = 500;
        $orders = [];
        if ($orders_query->count()) {
            $orders = $orders_query->get();
            $response = 200;
        }
        return response()->json([
            'status' => $response,
            'orders' => $orders,
        ]);
    }

    public function getPurchaseOrderList($order_id)
    {
        return PurchaseOrder::withoutGlobalScopes()->where('order_id', $order_id)->whereNull('deleted_at')->pluck('po_no', 'id');
    }

    public function getCuttingTableForCuttingPlan($id)
    {
        return CuttingTable::where('id', $id)->pluck('table_no', 'id');
    }

    public function getCuttingPlanData($id)
    {
        $orders = [];
        $purchase_orders = [];
        $colors = [];
        $orderId = '';
        $purchaseOrderId = '';
        $colorId = '';
        $cuttingPlan = [];
        $cuttingPlanQuery = CuttingPlan::withoutGlobalScopes()->where('id', $id)->whereNull('deleted_at');

        if ($cuttingPlanQuery->count()) {
            $cuttingPlan = $cuttingPlanQuery->first();
            $buyerId = $cuttingPlan->buyer_id;
            $orderId = $cuttingPlan->order_id;
            $purchaseOrderId = $cuttingPlan->purchase_order_id;
            $colorId = $cuttingPlan->color_id;

            $orders = Order::withoutGlobalScopes()
                ->where('buyer_id', $buyerId)
                ->whereNotNull('style_name')
                ->whereNull('deleted_at')
                ->get()
                ->map(function ($item) {
                    return [
                        'key' => $item->id,
                        'label' => $item->style_name,
                    ];
                });
            $purchase_orders = PurchaseOrder::withoutGlobalScopes()
                ->whereNull('deleted_at')
                ->where('order_id', $orderId)->get()
                ->map(function ($item) {
                    return [
                        'key' => $item->id,
                        'label' => $item->po_no,
                    ];
                });
            $colors = [];
            $color_qyery = PurchaseOrderDetail::withoutGlobalScope('factoryId')
                ->leftJoin('purchase_orders', 'purchase_orders.id', 'purchase_order_details.purchase_order_id')
                ->join('colors', 'purchase_order_details.color_id', 'colors.id')
                ->leftJoin('orders', 'orders.id', 'purchase_orders.order_id')
                ->where('purchase_orders.id', $purchaseOrderId)
                ->pluck('colors.name', 'colors.id')
                ->all();
            foreach ($color_qyery as $key => $val) {
                $colors[] = [
                    'key' => $key,
                    'label' => $val,
                ];
            }
        }

        return response()->json([
            "booking_no_list" => $orders,
            "po_list" => $purchase_orders,
            "color_list" => $colors,
            "order_id" => $orderId,
            "purchase_order_id" => $purchaseOrderId,
            "color_id" => $colorId,
            "cutting_plan" => $cuttingPlan,
        ]);
    }

    public function index($floor_id, $plan_date, $user_id, Request $request)
    {
        $cutting_plans = [];
        $buyer_list = [];
        $cutting_table = [];
        $booking_no_list = [];
        $board_colors = [];
        if ($floor_id) {
            $factory_id = CuttingFloor::withoutGlobalScopes()->findOrFail($floor_id)->factory_id;
            $from = Carbon::parse($plan_date)->startOfDay();
            $to = Carbon::parse($plan_date)->endOfDay();

            $cutting_plans = CuttingPlan::withoutGlobalScopes()
                ->where([
                    'factory_id' => $factory_id,
                    'plan_date' => $plan_date,
                    'cutting_floor_id' => $floor_id
                ])->whereNull('deleted_at')
                ->where("start_date", "<", $to)
                ->where("end_date", ">=", $from)
                ->get();

            if ($request->mode == 'week' || $request->mode == 'month') {
                $lastDateOfMonth = Carbon::parse($plan_date)->lastOfMonth()->toDateString();
                $cutting_plans = CuttingPlan::withoutGlobalScopes()
                    ->where([
                        'factory_id' => $factory_id,
                        'cutting_floor_id' => $floor_id
                    ])->whereNull('deleted_at')
                    ->whereDate('plan_date', '<=', $lastDateOfMonth)
                    ->get();
            }

            foreach ($cutting_plans as $key => $cutting_plan) {
                $cutting_plans[$key]['production'] = BundleCard::withoutGlobalScopes()
                    ->where([
                        'purchase_order_id' => $cutting_plan->purchase_order_id,
                        'color_id' => $cutting_plan->color_id,
                        'cutting_table_id' => $cutting_plan->cutting_table_id,
                        'factory_id' => $factory_id,
                        'status' => ACTIVE,
                    ])
                    ->whereNull('deleted_at')
                    ->where('updated_at', '>=', $cutting_plan->start_date)
                    ->where('updated_at', '<=', $cutting_plan->end_date)
                    ->selectRaw('SUM(quantity) - SUM(total_rejection) as cutting_production')
                    ->first()->cutting_production ?? 0;
            }

            $buyer_list[] = [
                'key' => '',
                'label' => 'Select Buyer',
            ];

            $booking_no_list = [];
            Order::withoutGlobalScopes()
                ->select('id', 'style_name')
                ->whereNull('deleted_at')
                ->whereNotNull('style_name')->get()
                ->each(function ($item, $k) use (&$booking_no_list) {
                    $booking_no_list[] = [
                        'key' => $item->id,
                        'label' => $item->style_name
                    ];
                });
            // Cutting Table
            $cutting_table = CuttingTable::withoutGlobalScopes()
                ->whereNull('deleted_at')
                ->selectRaw(' id as value, table_no as label')
                ->where('cutting_floor_id', $floor_id)
                ->get();
            $board_colors = [];
            $boardColorQuery = clone $cutting_plans;
            $boardColorQuery->each(function ($item, $i) use (&$board_colors) {
                $board_colors[] = [
                    'key' => $item->id,
                    'label' => $item->text,
                    'plan_date' => $item->plan_date,
                    'backgroundColor' => $item->board_color,
                    'plan_qty' => $item->plan_qty,
                    'production' => $item->production,
                ];
            });
        }

        return response()->json([
            "data" => $cutting_plans,
            "collections" => [
                "sections" => $cutting_table,
                "buyer_list" => $buyer_list,
                "booking_no_list" => $booking_no_list,
                "board_colors" => $board_colors,
            ]
        ]);
    }

    public function store($floor_id, $plan_date, $user_id, Request $request)
    {
        try {
            if (!$request->section_id) {
                return response()->json([
                    "action" => 'error'
                ]);
            }
            DB::beginTransaction();
            $cutting_table = CuttingTable::withoutGlobalScopes()->findOrFail($request->section_id);

            $floor_id = $cutting_table->cutting_floor_id;
            $factory_id = $cutting_table->factory_id;
            $rating = null;
            if ($request->rating != null) {
                $rating = $request->rating;
            }
            $no_of_marker = null;
            if ($request->no_of_marker != 'null') {
                $no_of_marker = $request->no_of_marker;
            }
            $smv = null;
            if ($request->smv != 'null') {
                $smv = $request->smv;
            }
            $start_time = strtotime($request->start_date);
            $end_time = strtotime($request->end_date);
            $duration = $end_time - $start_time;

            // Check If any plan is running in this date range
            $this->checkOtherRunningPlans($request, $factory_id);
            $plan_date = Carbon::parse($request->start_date)->toDateString();
            $cutting_plan = [
                'text' => '<div class="event-progress">' . preg_replace('/\s+/', '', strip_tags($request->plan_text)) . '</div>',
                'plan_text' => preg_replace('/\s+/', '', strip_tags($request->plan_text)),
                'start_date' => $request->start_date,
                'end_date' => $request->end_date,
                'duration' => $duration ?? 300,
                'plan_date' => $plan_date,
                'cutting_floor_id' => $floor_id,
                'cutting_table_id' => $request->section_id,
                'section_id' => $request->section_id,
                'buyer_id' => $request->buyer_id,
                'order_id' => $request->order_id,
                'purchase_order_id' => $request->purchase_order_id,
                'color_id' => $request->color_id,
                'plan_qty' => $request->plan_qty,
                'board_color' => $request->board_color,
                'no_of_marker' => $no_of_marker,
                'rating' => $rating,
                'smv' => $smv,
                'factory_id' => $factory_id,
                'created_by' => $user_id,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ];
            $save = DB::table('cutting_plans')->insertGetId($cutting_plan);
            if ($save) {
                $action = "inserted";
                DB::commit();
                return response()->json([
                    "action" => $action,
                    "tid" => $save
                ]);
            } else {
                DB::rollback();
                $action = "error";
                return response()->json([
                    "action" => $action
                ]);
            }
        } catch (Excception $e) {
            DB::rollback();
            $action = "error";
            return response()->json([
                "action" => $action
            ]);
        }
    }

    public function update($floor_id, $plan_date, $user_id, $id, Request $request)
    {
        try {
            DB::beginTransaction();
            $cutting_table = CuttingTable::withoutGlobalScopes()->findOrFail($request->section_id);
            $floor_id = $cutting_table->cutting_floor_id;
            $factory_id = $cutting_table->factory_id;
            $smv = null;
            if ($request->smv != 'null') {
                $smv = $request->smv;
            }

            $cutting_table = CuttingTable::withoutGLobalScopes()->where('id', $request->section_id)->first();

            if ($floor_id != $cutting_table->cutting_floor_id) {
                $floor_id = $cutting_table->cutting_floor_id;
            }
            $start_time = strtotime($request->start_date);
            $end_time = strtotime($request->end_date);
            $duration = $end_time - $start_time;
            // Check If any plan is running in this date range
            $this->checkOtherRunningPlans($request, $factory_id);
            $plan_date = Carbon::parse($request->start_date)->toDateString();
            $cutting_plan = [
                'text' => '<div class="event-progress">' . preg_replace('/\s+/', '', strip_tags($request->plan_text)) . '</div>',
                'plan_text' => preg_replace('/\s+/', '', strip_tags($request->plan_text)),
                'start_date' => $request->start_date,
                'end_date' => $request->end_date,
                'duration' => $duration ?? 300,
                'plan_date' => $plan_date,
                'cutting_floor_id' => $floor_id,
                'cutting_table_id' => $request->section_id,
                'section_id' => $request->section_id,
                'buyer_id' => $request->buyer_id,
                'order_id' => $request->order_id,
                'purchase_order_id' => $request->purchase_order_id,
                'color_id' => $request->color_id,
                'plan_qty' => $request->plan_qty,
                'no_of_marker' => $request->no_of_marker,
                'rating' => $request->rating,
                'smv' => $smv,
                'board_color' => $request->board_color,
                'updated_by' => $user_id,
                'updated_at' => Carbon::now(),
            ];
            $update = DB::table('cutting_plans')->where('id', $id)->update($cutting_plan);
            if ($update) {
                $action = "updated";
                DB::commit();
                return response()->json([
                    "action" => $action,
                    "tid" => $id
                ]);
            } else {
                DB::rollback();
                $action = "error";
                return response()->json([
                    "action" => $action,
                    "tid" => $id
                ]);
            }
        } catch (Excception $e) {
            DB::rollback();
            $action = "error";
            return response()->json([
                "action" => $action,
                "tid" => $id
            ]);
        }
    }

    public function destroy($floor_id, $plan_date, $user_id, $id)
    {
        $cutting_plan = DB::table('cutting_plans')->where('id', $id);
        $cutting_plan->update([
            'deleted_at' => Carbon::now(),
            'deleted_by' => $user_id
        ]);

        return response()->json([
            "action" => "deleted"
        ]);
    }

    private function checkOtherRunningPlans($request, $factory_id)
    {
        $cutting_plan_query = DB::table('cutting_plans')
            ->where([
                'cutting_table_id' => $request->section_id,
                'factory_id' => $factory_id,
            ])
            ->whereNull('deleted_at')
            ->where('start_date', '<', $request->end_date)
            ->where('end_date', '>', $request->start_date);
        $cutting_plan_count = $cutting_plan_query->count();
        if ($cutting_plan_count) {
            $cutting_plans = $cutting_plan_query->orderBy('start_date', 'asc')->get();
            foreach ($cutting_plans as $cutting_plan) {
                $cut_plan_id = $cutting_plan->id;
                $requested_id = $request->id ?? '';
                if ($cut_plan_id == $requested_id) {
                    continue;
                }
                $this->updateNextPlansData($request, $cutting_plan, $factory_id);
            }
        }
        $data = [
            'cutting_plan_count' => $cutting_plan_count,
            'query' => $cutting_plan_query
        ];

        return $data;
    }

    private function updateNextPlansData($request, $cutting_plan, $factory_id)
    {
        $update_plan_id = $cutting_plan->id;
        $update_plan_data = [
            'plan_date' => date('Y-m-d', strtotime($request->end_date)),
            'start_date' => $request->end_date,
            'end_date' => Carbon::parse($request->end_date)->add(new DateInterval('PT' . ($cutting_plan->duration ?? 300) . 'S'))
        ];
        DB::table('cutting_plans')->where('id', $update_plan_id)->update($update_plan_data);
        $cutting_update_data = DB::table('cutting_plans')->where('id', $update_plan_id)->first();
        $this->checkOtherRunningPlans($cutting_update_data, $factory_id);
        return true;
    }
}
