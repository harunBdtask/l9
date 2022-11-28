<?php


namespace SkylarkSoft\GoRMG\Commercial\Controllers\ExportInvoices;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Http\JsonResponse;
use App\Constants\ApplicationConstant;
use SkylarkSoft\GoRMG\Commercial\Models\ExportLC;
use SkylarkSoft\GoRMG\SystemSettings\Models\Buyer;
use SkylarkSoft\GoRMG\Commercial\Models\SalesContract;
use SkylarkSoft\GoRMG\Merchandising\Models\PurchaseOrder;
use SkylarkSoft\GoRMG\Commercial\Models\ExportInvoiceDetail;

class LCOrSCSearchController
{
    public function __invoke(Request $request): JsonResponse
    {
        $search = $request->get('search_by') == ApplicationConstant::SEARCH_BY_LC ? ExportLC::query() : SalesContract::query();
        $searchBy = $request->get('search_by');
        $lc_sc_no = $request->get('lc_sc_no') ?? null;

        $search->with([
            // 'beneficiary', 'buyer.country', 'applicant', 'details.po', 'details.order.order.dealingMerchant', 'details.po.poDetails',
            'beneficiary', 'applicant', 'details.po', 'details.order.order.dealingMerchant', 'details.po.poDetails',
        ])->when($request->get('factory_id'), function ($query) use ($request) {
            $query->where('factory_id', $request->get('factory_id'));
        })->when($request->get('buyer_id'), function ($query) use ($request) {
            $query->whereJsonContains('buyer_id', $request->get('buyer_id'));
        })->when(($lc_sc_no && $searchBy == ApplicationConstant::SEARCH_BY_LC), function ($query) use ($lc_sc_no) {
            $query->where('lc_number', $lc_sc_no);
        })->when(($lc_sc_no && $searchBy == ApplicationConstant::SEARCH_BY_SC), function ($query) use ($lc_sc_no) {
            $query->where('contract_number', $lc_sc_no);
        });

        
        $data = collect($search->get())->map(function ($elc) use ($request, $searchBy) {

            if(is_array($elc['buyer_id'])){
                $buyerInfo = Buyer::whereIn('id', $elc['buyer_id'])->with('country')->first();
            }else{
                $buyerInfo = Buyer::where('id', $elc['buyer_id'])->with('country')->first();
            }

            return [
                'id' => null,
                'unique_id' => null,
                'file_no' => $elc['internal_file_no'],
                'lc_sc_no' => $searchBy == ApplicationConstant::SEARCH_BY_LC ? $elc['lc_number'] : $elc['contract_number'],
                'export_lc_id' => $searchBy == ApplicationConstant::SEARCH_BY_LC ? $elc['id'] : null,
                'sales_contract_id' => $searchBy == ApplicationConstant::SEARCH_BY_SC ? $elc['id'] : null,
                'beneficiary_id' => $elc['beneficiary_id'],
                'beneficiary' => $elc['beneficiary']['factory_name'] ?? '',
                'buyer_id' => $buyerInfo->id,
                'buyer' => $buyerInfo->name,
                'lien_bank_id' => $elc['lien_bank_id'],
                'lien_bank' => $elc->lienBank->name ?? null,
                'applicant_id' => $elc['applicant_id'],
                'applicant' => $elc['applicant']['name'],
                'location' => $elc['beneficiary']['factory_address'],
                'country_id' => $buyerInfo->country_id,
                'country' => $buyerInfo->country->name,
                'country_code' => $buyerInfo->country->iso_alpha_2_code,
                'year' => $elc->year,
                'details' => collect($elc->details)->map(function ($detail) use ($elc, $request, $searchBy) {
                    $po_id = $detail->po->count() ? $detail->po->id : null;
                    $po_details = $detail->po->poDetails->first();
                    $cumu_invoice_qty = $po_id ? ExportInvoiceDetail::query()->where('po_id', $po_id)->sum('current_invoice_qty') : 0;
                    $cumu_invoice_value = $po_id ? ExportInvoiceDetail::query()->where('po_id', $po_id)->sum('current_invoice_value') : 0;
                    $po_balance_qty = $detail->attach_qty - $cumu_invoice_qty;

                    return [
                        'export_lc_id' => $searchBy == ApplicationConstant::SEARCH_BY_LC ? $elc['id'] : null,
                        'export_lc_detail_id' => $searchBy == ApplicationConstant::SEARCH_BY_LC ? $detail['id'] : null,
                        'sales_contract_id' => $searchBy == ApplicationConstant::SEARCH_BY_SC ? $elc['id'] : null,
                        'sales_contract_detail_id' => $searchBy == ApplicationConstant::SEARCH_BY_SC ? $detail['id'] : null,
                        'order_id' => $detail->order_id,
                        'po_id' => $detail->po->id,
                        'po_no' => $detail->po->po_no,
                        'article_no' => collect($po_details->quantity_matrix)->where('particular', PurchaseOrder::ARTICLE_NO)->values()->first()['value'] ?? null,
                        'shipment_date' => date('Y-m-d', strtotime($detail->po->ex_factory_date)),
                        'attach_qty' => $detail->attach_qty,
                        'rate' => $detail->rate,
                        'fixed_rate' => $detail->rate,
                        'current_invoice_qty' => null,
                        'current_invoice_value' => null,
                        'cumu_invoice_qty' => $cumu_invoice_qty,
                        'fixed_cumu_invoice_qty' => $cumu_invoice_qty,
                        'po_balance_qty' => $po_balance_qty,
                        'cumu_invoice_value' => $cumu_invoice_value,
                        'fixed_cumu_invoice_value' => $cumu_invoice_value,
                        'ex_factory_qty' => null,
                        'merchandiser_id' => $detail->order->order->dealing_merchant_id,
                        'merchandiser' => $detail->order->order->dealingMerchant->full_name_with_email,
                        'production_source' => 1,
                        'color_size_details' => [],
                        'color_size_details_status' => 0,
                        'factory_id' => factoryId(),
                    ];
                }),
            ];
        });

        return response()->json($data, Response::HTTP_OK);
    }
}
