<?php

namespace SkylarkSoft\GoRMG\Merchandising\Controllers\PriceQuotation;

use App\Http\Controllers\Controller;
use DB;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use SkylarkSoft\GoRMG\Merchandising\Models\CostingDetails;
use SkylarkSoft\GoRMG\Merchandising\Models\PqCommissionDetail;
use SkylarkSoft\GoRMG\Merchandising\Models\PriceQuotation;
use SkylarkSoft\GoRMG\Merchandising\Requests\PqCommissionCostRequest;
use Symfony\Component\HttpFoundation\Response;
use Throwable;

class PQCommissionCostController extends Controller
{
    public function index($quotation_id): JsonResponse
    {
        try {
            $total_amount = 0;
            $commission_costs = [];
            PqCommissionDetail::query()
                ->where('quotation_id', $quotation_id)
                ->get()
                ->map(function ($item) use (&$commission_costs, &$total_amount) {
                    $commission_costs[] = [
                        'id' => $item->id,
                        'quotation_id' => $item->quotation_id,
                        'particular' => PqCommissionDetail::PARTICULARS[$item->particular],
                        'commission_base' => PqCommissionDetail::COMMISSION_BASES[$item->commission_base],
                        'commission_rate' => $item->commission_rate,
                        'amount' => $item->amount,
                        'status' => PqCommissionDetail::STATUS[$item->status],
                    ];
                    $total_amount += $item->amount;
                });

            return response()->json([
                'status' => 'success',
                'commission_costs' => $commission_costs,
                'total_amount' => round($total_amount, 2),
                'error' => null,
            ]);
        } catch (Exception $e) {
            return response()->json([
                'status' => 'danger',
                'commission_costs' => $commission_costs,
                'error' => $e->getMessage(),
            ]);
        }
    }

    /**
     * @param PqCommissionCostRequest $request
     * @return JsonResponse
     * @throws Throwable
     */
    public function store(PqCommissionCostRequest $request): JsonResponse
    {
        try {
            DB::beginTransaction();
            $pq_commission_cost = new PqCommissionDetail();
            $pq_commission_cost->quotation_id = $request->quotation_id;
            $pq_commission_cost->particular = $request->particular;
            $pq_commission_cost->commission_base = $request->commission_base;
            $pq_commission_cost->commission_rate = $request->commission_rate;
            $pq_commission_cost->amount = $request->amount;
            $pq_commission_cost->status = $request->status;
            $pq_commission_cost->save();
            $this->updatePriceQuotationCommissionPerDzn($request->quotation_id);
            DB::commit();

            return response()->json([
                'status' => 'success',
                'message' => "Data stored successfully",
                'error' => null,
            ]);
        } catch (Exception $e) {
            return response()->json([
                'status' => 'danger',
                'message' => "Something went wrong!",
                'error' => $e->getMessage(),
            ]);
        }
    }

    private function updatePriceQuotationCommissionPerDzn($quotation_id)
    {
        $commission_amount = PqCommissionDetail::query()
            ->where(
                [
                    'quotation_id' => $quotation_id,
                    'status' => 1, // Active status
                ]
            )->sum('amount');
        PriceQuotation::query()->where('quotation_id', $quotation_id)->update([
            'commi_dzn' => round($commission_amount, 2),
        ]);
    }

    /**
     * @param $id
     * @param Request $request
     * @return JsonResponse
     * @throws Throwable
     */

    public function destroy($id, Request $request): JsonResponse
    {
        try {
            DB::beginTransaction();
            $pq_commission_cost = PqCommissionDetail::query()->findOrFail($id);
            $quotation_id = $pq_commission_cost->quotation_id;
            $pq_commission_cost->forceDelete();
            $this->updatePriceQuotationCommissionPerDzn($quotation_id);
            DB::commit();

            return response()->json([
                'status' => 'success',
                'message' => "Data deleted successfully",
                'error' => null,
            ]);
        } catch (Exception $e) {
            return response()->json([
                'status' => 'danger',
                'message' => "Something went wrong!",
                'error' => $e->getMessage(),
            ]);
        }
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function save(Request $request): JsonResponse
    {
        $data['details'] = $request->get('commissionCosts');
        $data['calculation']['total'] = $request->get('total_amount');
        $details = $data;
        $mainData['type'] = 'commission_cost';
        $mainData['details'] = $details;
        $mainData['price_quotation_id'] = $request->get('price_quotation_id');
        $cost_details = CostingDetails::query()->where("price_quotation_id", $request->get('price_quotation_id'))
            ->where("type", 'commission_cost')
            ->first();
        if (!$cost_details) {
            $type = "Created";
            CostingDetails::query()->create($mainData);
        } else {
            $type = "Updated";
            $cost_details->fill($mainData)->save();
        }

        return response()->json([
            'status' => Response::HTTP_CREATED,
            'message' => "Data {$type} Successfully",
            'data' => $mainData,
        ]);
    }
}
