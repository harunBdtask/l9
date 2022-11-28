<?php

namespace SkylarkSoft\GoRMG\Inventory\Controllers\API\YarnReceive;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;
use Symfony\Component\HttpFoundation\Response;
use SkylarkSoft\GoRMG\SystemSettings\Models\Currency;
use SkylarkSoft\GoRMG\SystemSettings\Models\YarnType;
use SkylarkSoft\GoRMG\SystemSettings\Models\YarnCount;
use SkylarkSoft\GoRMG\Commercial\Models\ProformaInvoice;
use SkylarkSoft\GoRMG\SystemSettings\Models\YarnComposition;
use SkylarkSoft\GoRMG\SystemSettings\Models\UnitOfMeasurement;
use SkylarkSoft\GoRMG\SystemSettings\Models\Item;

class YarnProformaInvoiceApiController extends Controller
{
    public function __invoke(Request $request): JsonResponse
    {
        try {
            $proforma_invoice_list=[];
            $item = Item::query()->where('item_name','yarn')->first();
            $proforma_invoice = ProformaInvoice::query()
                ->where('item_category', $item->id)
//                ->where('pi_basis',2)
                ->where('factory_id', request('factory_id'));
            if ($request->get('table')) {
                $get_proforma_invoice = $proforma_invoice->with('supplier')
                    ->when(request('pi_number'), function ($query) {
                        $query->where('id', request('pi_number'));
                    })
                    ->when(request('to_date') && request('from_date'), function ($query) {
                        $query->whereBetween('pi_receive_date', [request('from_date'), request('to_date')]);
                    })
                    ->orderByDesc('id')
                    ->get()->filter(function ($filter) {
                        return (bool)collect(optional($filter->details)->details)->count();
                    })->map(function ($pi) {
                        $currency = Currency::query()->where('currency_name', $pi->currency)->first();
                        return [
                            'receive_basis_id' => $pi->id,
                            'currency_id'      => optional($currency)->id,
                            'receive_basis_no' => $pi->pi_no,
                            'source'           => $pi->source,
                            'currency_name'    => $pi->currency,
                            'lc_no'            => $pi->lc_group_no,
                            'lc_receive_date'  => $pi->lc_receive_date,
                            'date'             => $pi->pi_receive_date,
                            'details'          => collect($pi->details)->count()
                        ];
                    });
            } else {
                $get_proforma_invoice = $proforma_invoice->get()->map(function ($proforma_invoice){
                    return array('id'=>$proforma_invoice->id, 'text'=>$proforma_invoice->pi_no);
                });
            }
            foreach ($get_proforma_invoice as $item){ $proforma_invoice_list[]=$item; }
            return response()->json($proforma_invoice_list, Response::HTTP_OK);
        } catch (\Exception $exception) {
            return response()->json($exception->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
