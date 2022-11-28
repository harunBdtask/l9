<?php

namespace SkylarkSoft\GoRMG\Commercial\Services\Report;

use Carbon\Carbon;
use SkylarkSoft\GoRMG\Commercial\Models\B2BMarginLC;
use SkylarkSoft\GoRMG\Commercial\Models\ProformaInvoice;

class CommercialReportService
{
    public static function btbStatus($request)
    {

        $data = collect();
        if ($request->has(['factory', 'year','month'])) {

            $data = B2BMarginLC::query()
            ->with([
                'details'=>function($q) use ($request){
                    return $q
                    ->when($request->get('buyer'), function($a) use ($request){
                        $a->whereJsonContains('buyer_id', $request->get('buyer'));
                    })
                    ->where(function($b) {
                        $b->whereNotNull('primary_master_contract_id')->orWhereNotNull('sales_contract_id')->with(['primaryMasterContract.buyingAgent', 'salesContract.buyingAgent']);
                    });
                },
                'btbLcAmends',
                'importLcDocument.importPayment',
                'buyingAgent',
                'supplier:id,name'
                ])
            ->where('factory_id', $request->get('factory'))
            ->whereYear('lc_date', $request->get('year'))
            ->whereMonth('lc_date', $request->get('month'))
            ->get();
                
        }
        // return $data;
        return self::btb_status_format($data);
    }

    public static function btb_status_format($data)
    {
        return collect($data)->map(function($item){

            $piData = ProformaInvoice::query()->whereIn('id', $item->pi_ids)->get()->map(function($pi){
                return [
                    'pi_date' => $pi->pi_receive_date,
                    'pi_no' => $pi->pi_no,
                    'pi_amnt' => @$pi->details->total??null
                ];
            });

            //Imp Docs
            $imp_docs = collect($item->importLcDocument)->map(function($imp){
                return [
                    'imp_accept_amnt' => $imp->document_value,
                    'payments' => collect($imp->importPayment)->map(function($pay){
                        return [
                            'payment_date' => Carbon::parse($pay->payment_date)->format('d M Y'),
                            'payment_amnt' => $pay->accepted_amount
                        ];
                    })
                ];
            });

            //Primary and Sales Contract
            $contracts = collect($item->details)->map(function($contract){

                
                $info['pmc'] = [
                    'pmc_no' => $contract->primaryMasterContract->ex_contract_number,
                    'pmc_value' => $contract->primaryMasterContract->contract_value,
                    'buying_agent'=> $contract->primaryMasterContract->buyingAgent->buying_agent_name,
                    'buyer'=> collect($contract->buyer_names)->implode('name',',')
                ];

                $info['sc'] = [
                    'sc_no' => $contract->salesContract->contract_number,
                    'sc_value' => $contract->salesContract->contract_value,
                    'buying_agent'=> $contract->salesContract->buyingAgent->buying_agent_name,
                    'buyer'=> collect($contract->buyer_names)->implode('name',',')
                ];

                return $info;
            });

            return [
                'btb_lc_date' => Carbon::parse($item->lc_date)->format('d M Y'),
                'btb_lc_no' => $item->lc_number,
                'supplier' => $item->supplier->name,
                'btb_lc_amnt' => $item->lc_value,
                'btb_lc_value' => collect($item->details)->sum('lc_sc_value')??null,
                'btb_lc_amends' => $item->btbLcAmends,
                'pi_details' => $piData,
                'imp_docs'=> $imp_docs,
                'contracts' => $contracts
            ];

        });
    }
}