<?php

namespace SkylarkSoft\GoRMG\Inventory\Controllers\API\YarnReceive;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;
use SkylarkSoft\GoRMG\SystemSettings\Models\CompositionType;
use SkylarkSoft\GoRMG\SystemSettings\Models\Currency;
use SkylarkSoft\GoRMG\Merchandising\Models\YarnPurchaseOrder;
use SkylarkSoft\GoRMG\SystemSettings\Models\UnitOfMeasurement;
use Symfony\Component\HttpFoundation\Response;

class YarnWorkOrderApiController extends Controller
{

    public function __invoke(Request $request): JsonResponse
    {
        try {
            $yarn_purchase_order_list=[];
            $yarn_purchase_order = YarnPurchaseOrder::query()
                ->when(request('wo_number'),function ($query){
                    $query->where('id', request('wo_number'));
                })
                ->where('factory_id', request('factory_id'));
            if ($request->get('table')) {
                $get_yarn_purchase_order = $yarn_purchase_order
                    ->with([
                        'details.unitOfMeasurement',
                        'details.yarnComposition',
                        'details.yarnCount',
                        'details.yarnType',
                    ])
                    ->orderByDesc('id')
                    ->get()
                    ->map(function ($wo) {
                        $currency = Currency::query()->where('currency_name', $wo->currency)->first();
                        return [
                            'lc_no'            => null,
                            'receive_basis_id' => $wo->id,
                            'receive_basis_no' => $wo->wo_no,
                            'source'           => $wo->source,
                            'date'             => $wo->wo_date,
                            'currency_name'    => $wo->currency,
                            'currency_id'      => optional($currency)->id,
                            'details'          => collect($wo->details)->count()
                        ];
                    });
            } else {
                $get_yarn_purchase_order = $yarn_purchase_order->orderByDesc('id')
                    ->get(['id', 'wo_no as text', 'pay_mode', 'source'])
                    ->makeHidden(['source_value', 'pay_mode_value', 'pay_mode', 'source']);
            }
            foreach ($get_yarn_purchase_order as $item){ $yarn_purchase_order_list[]=$item; }
            return response()->json($yarn_purchase_order_list, Response::HTTP_OK);
        } catch (\Exception $exception) {
            return response()->json($exception->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
