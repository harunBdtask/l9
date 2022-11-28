<?php


namespace SkylarkSoft\GoRMG\Commercial\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use SkylarkSoft\GoRMG\DyesStore\Models\DsItem;
use Symfony\Component\HttpFoundation\Response;
use SkylarkSoft\GoRMG\SystemSettings\Models\Item;
use SkylarkSoft\GoRMG\SystemSettings\Models\Buyer;
use SkylarkSoft\GoRMG\Commercial\Models\AccountHead;
use SkylarkSoft\GoRMG\SystemSettings\Models\Country;
use SkylarkSoft\GoRMG\SystemSettings\Models\Factory;
use SkylarkSoft\GoRMG\SystemSettings\Models\Currency;
use SkylarkSoft\GoRMG\SystemSettings\Models\Incoterm;
use SkylarkSoft\GoRMG\SystemSettings\Models\LienBank;
use SkylarkSoft\GoRMG\Commercial\Models\ProformaInvoice;
use SkylarkSoft\GoRMG\Commercial\Models\CommercialVariable;
use SkylarkSoft\GoRMG\Merchandising\Models\YarnPurchaseOrder;
use SkylarkSoft\GoRMG\SystemSettings\Models\BuyingAgentModel;
use SkylarkSoft\GoRMG\Commercial\Constants\CommercialConstant;
use SkylarkSoft\GoRMG\Merchandising\Models\Bookings\TrimsBooking;
use SkylarkSoft\GoRMG\Merchandising\Models\Bookings\FabricBooking;
use SkylarkSoft\GoRMG\Merchandising\Models\Bookings\TrimsBookingDetails;

class CommercialCommonAPI
{
    public function lienBanks(): JsonResponse
    {
        $banks = LienBank::all(['id', 'name as text', 'address']);

        return response()->json($banks);
    }

    public function countries(): JsonResponse
    {
        $countries = Country::all(['id', 'name as text', 'iso_alpha_2_code as code']);

        return response()->json($countries);
    }

    public function accountHeads(): JsonResponse
    {
        $accountHeads = AccountHead::all(['id', 'name as text']);

        return response()->json($accountHeads);
    }

    public function incoTerms()
    {
        $incoTerms = Incoterm::all(['id', 'incoterm as text']);

        return response()->json($incoTerms);
    }

    public function getCurrencies()
    {
        $currencies = Currency::query()->get(['id', 'currency_name as text']);
        return response()->json($currencies);
    }

    public function getBuyingAgent()
    {
        $buyingAgents = BuyingAgentModel::query()->get(['id', 'buying_agent_name as text']);
        return response()->json($buyingAgents);
    }

    public function getBtbLimit($factoryId)
    {
        $btb_limit_percent = $factoryId ? CommercialVariable::where(['variable_name' => 'btb_limit_percent', 'factory_id' => $factoryId])->first()['value'] ?? 0 : 0;
        return response()->json($btb_limit_percent);
    }

    public function getFactories()
    {
        $factories = Factory::query()->get(['id', 'factory_name as text']);
        return response()->json($factories);
    }

    public function fetchWorkWorkOrdersTrims($factoryId, $buyerId)
    {
        return TrimsBooking::query()
                    ->where('factory_id', $factoryId)
                    ->where('buyer_id', $buyerId)
                    ->has('bookingDetails')
                    ->pluck('unique_id')
                    ->unique()
                    ->values();
    }

    public function fetchWorkWorkOrdersFabrics($factoryId, $buyerId)
    {
        return FabricBooking::query()
                    ->where('factory_id', $factoryId)
                    ->where('buyer_id', $buyerId)
                    ->has('detailsBreakdown')
                    ->pluck('unique_id')
                    ->unique()
                    ->values();
    }

    public function fetchWorkWorkOrdersYarn($factoryId, $buyerId)
    {
        return YarnPurchaseOrder::query()
                    ->where('factory_id', $factoryId)
                    ->where('buyer_id', $buyerId)
                    ->has('details')
                    ->pluck('wo_no')
                    ->unique()
                    ->values();
    }

    public function getDyesStoreItems()
    {
        $data = DsItem::query()->with('uomDetails')->get()
        ->map(function ($val) {
            return[
                'id' => $val->id,
                'text' => $val->name,
                'uom_id' => $val->uom,
                'uom_name' => $val->uomDetails->name??'',
            ];
        });
        return response()->json($data);
    }

        public function fetchItemPIList(Request $request): JsonResponse
        {
            $itemType = $request->get('itemType')??'Chemicals';
            try {
                $proforma_invoice_list=[];
                $item = Item::query()->where('item_name', $itemType)->first();
                $proforma_invoice = ProformaInvoice::query()
                    ->where('item_category', $item->id)
                    ->where('factory_id', factoryId());
                if ($request->get('table')) {
                    $get_proforma_invoice = $proforma_invoice
                        ->when(request('pi_number'), function ($query) {
                            $query->where('id', request('pi_number'));
                        })
                        ->when(request('to_date') && request('from_date'), function ($query) {
                            $query->whereBetween('pi_receive_date', [request('from_date'), request('to_date')]);
                        })
                        ->orderByDesc('id')
                        ->get()
                        // ->filter(function ($filter) {
                        //     return (bool)collect(optional($filter->details)->details)->count();
                        // })
                        ->map(function ($pi) {
                            $currency = Currency::query()->where('currency_name', $pi->currency)->first();


                            $detailsList = isset($pi->details->details) ? collect($pi->details->details)->map(function($piItem){

                                $dsItem = DsItem::query()->with(['uomDetails','category'])->find($piItem->dyes_store_item);
                                if($dsItem){
                                    $piItem->item_id =  $dsItem->id;
                                    $piItem->item_name =  $dsItem->name;
                                    $piItem->uom_id =  $dsItem->uomDetails->id??$dsItem->uom;
                                    $piItem->uom_name =  $dsItem->uomDetails->name??'';
                                    $piItem->category_id =  $dsItem->category_id;
                                    $piItem->category_name =  $dsItem->category->name;
                                    $piItem->rate_taka =  $piItem->usd_value * $piItem->exchange_rate;
                                    return $piItem;
                                }
                            })->filter():[];


                            return [
                                'receive_basis_id' => $pi->id,
                                'receive_basis_no' => $pi->pi_no,
                                'supplier_id'      => $pi->supplier_id,
                                'source'           => $pi->source?CommercialConstant::SOURCES[$pi->source]:'',
                                'currency_id'      => optional($currency)->id,
                                'currency_name'    => $pi->currency,
                                'lc_no'            => $pi->lc_group_no??null,
                                'lc_receive_date'  => $pi->lc_receive_date??null,
                                'date'             => $pi->pi_receive_date,
                                'details'          => $pi->details? collect($pi->details)->count():0,
                                'detailsList'      => $detailsList??[]
                            ];
                        });
                    foreach ($get_proforma_invoice as $item){ $proforma_invoice_list[]=$item; }
                } else {
                    $proforma_invoice_list = $proforma_invoice->get()->map(function ($proforma_invoice){
                        return array('id'=>$proforma_invoice->id, 'text'=>$proforma_invoice->pi_no);
                    });
                }

                return response()->json($proforma_invoice_list, Response::HTTP_OK);
            } catch (\Exception $exception) {
                return response()->json($exception->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
            }
        }

    public function get_buyer(Buyer $buyer)
    {
        return response()->json($buyer);
    }
    public function get_lien_bank(LienBank $bank)
    {
        return response()->json($bank);
    }

}
