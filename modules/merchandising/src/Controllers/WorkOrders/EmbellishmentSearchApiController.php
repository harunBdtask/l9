<?php


namespace SkylarkSoft\GoRMG\Merchandising\Controllers\WorkOrders;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use SkylarkSoft\GoRMG\Merchandising\Models\Budget;
use SkylarkSoft\GoRMG\Merchandising\Models\WorkOrders\EmbellishmentBookingItemDetails;
use SkylarkSoft\GoRMG\SystemSettings\Models\MerchandisingVariableSettings;

class EmbellishmentSearchApiController extends Controller
{
    public function __invoke(Request $request)
    {
        try {
            $types = [
                'embellishment_cost',
                'wash_cost',
            ];

            $emblActionStatus = MerchandisingVariableSettings::query()->where(['factory_id'=> factoryId(), 'buyer_id' => $request->get('buyer_id')])->first();
            $emblActionStatus = isset($emblActionStatus) ? $emblActionStatus['variables_details']['budget_approval_required_for_booking']['embellishment_part'] : null;

            $budget = Budget::query()
                ->when($request->get('buyer_id'), function ($query) use ($request) {
                    $query->where('buyer_id', $request->get('buyer_id'));
                })->when($request->get('unique_id'), function ($query) use ($request) {
                    $query->where('job_no', 'LIKE', '%' . $request->get('unique_id') . '%');
                })->when($request->get('style_name'), function ($query) use ($request) {
                    $query->where('style_name', $request->get('style_name'));
                })->when($request->get('internal_ref'), function ($query) use ($request) {
                    $query->where('internal_ref', $request->get('internal_ref'));
                })->with(['costings' => function ($costing) use ($types) {
                    $costing->whereIn('type', $types);
                }, 'order.purchaseOrders' => function ($purchase) use ($request) {
                    $purchase->when($request->get('po_no'), function ($purchase) use ($request) {
                        $purchase->where('po_no', $request->get('po_no'));
                    });
                }])->get();
            $type = (int)$request->get('embellishment_name');
            $search_response = $budget->flatMap(function ($value) use ($type, $emblActionStatus) {
                $order_po = collect($value->order->purchaseOrders)->pluck('po_no');
                $po = $order_po->implode(',');

                return collect($value->costings)->pluck('details.details')
                    ->flatten(1)
                    ->filter(function ($search) use ($type, $emblActionStatus) {
                        return $type ? isset($search['name_id']) && $search['name_id'] == $type : $search;
                    })
                    ->map(function ($search) use ($value, $po, $order_po, $emblActionStatus) {

                        $po_wise_search = isset($search['breakdown']) ? collect($search['breakdown']['details'])
                            ->whereIn('po_no', $order_po) : [];

                        $workOrderQty = EmbellishmentBookingItemDetails::where([
                            'item_id' => $search['name_id'] ?? null,
                            'item_type_id' => $search['type_id'] ?? null,
                            'budget_unique_id' => $value['job_no'],
                        ])->sum('qty');

                        $totalQty = isset($search['breakdown']) ? collect($search['breakdown']['details'])
                            ->whereIn('po_no', explode(",", $po))
                            ->sum('total_qty') : 0.00;


                        $balanceQty = format($totalQty) - format($workOrderQty);


                        return collect($search)->merge([
                            'breakdown' => isset($search['breakdown']) ? collect($search['breakdown'])
                                ->merge(['details' => $po_wise_search]) : [],
                            'budget' => $value,
                            'po' => $po,
                            'work_order_qty' => $workOrderQty,
                            'total_qty' => $totalQty,
                            'balance_qty' => $balanceQty,
                            'emblActionStatus' => $emblActionStatus,
                        ]);
                    });
            });

            return response($search_response, Response::HTTP_OK);
        } catch (\Exception $exception) {
            return response()->json($exception->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
