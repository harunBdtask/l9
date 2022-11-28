<?php

namespace SkylarkSoft\GoRMG\Commercial\Services\Report;

use Carbon\Carbon;
use SkylarkSoft\GoRMG\Commercial\Models\B2BMarginLC;
use SkylarkSoft\GoRMG\Commercial\Models\ProformaInvoice;

class BTBStatusReportService
{

    public static function fetchData($request)
    {
        $fromDate = $request->from_date ? Carbon::parse($request->from_date)->format('Y-m-d') : Carbon::today()->format('Y-m-d');
        $toDate = $request->from_date ? Carbon::parse($request->to_date)->format('Y-m-d') : Carbon::today()->format('Y-m-d');
//       dd($fromDate);
         $data = B2BMarginLC::query()
            ->whereBetween('lc_date', [$fromDate, $toDate])
            ->with(['details' => function ($q) {
                return $q->whereNotNull('primary_master_contract_id')->with('primaryMasterContract.buyingAgent', 'primaryMasterContract.details');
            }, 'item'])
//            ->whereHas('details.primaryMasterContract')
            ->get();
        return self::formatData($data);
    }

    public static function formatData($data)
    {
        return collect($data)->map(function ($item) {
            $piData = ProformaInvoice::query()->whereIn('id', $item->pi_ids)->get()->map(function ($pi) {
                return [
                    'type' => $pi->lc_from,
                    'amount' => $pi->details->total??0,
                ];
            });
            return [
                'btb_lc_no' => $item->lc_number ?? null,
                'btb_lc_date' => $item->lc_date ?? null,
                'beneficiary' => null,
                'material_item' => $item->item->item_name ?? null,
                'local' => 0,
                'foreign' => 0,
                'btb_lc_value' => $item->lc_value ?? 0,
                'shipping_date' => $item->last_shipment_date ?? null,
                'lc_expiry_date' => $item->lc_expiry_date ?? null,
                'pi_details' => $piData,
                'pmc_data' => collect($item->details)->map(function ($pmc) {
                    return [
                        'buying_agent' => $pmc->primaryMasterContract->buyingAgent->buying_agent_name ?? null,
                        'pmc_id' => $pmc->primaryMasterContract->unique_id ?? null,
                        'lc_date' => $pmc->primaryMasterContract->ex_cont_issue_date ?? null,
                        'rate' => 0,
                        'order_qty' => collect($pmc->primaryMasterContract->details)->sum('order_qty') ?? 0,
                        'order_value' => collect($pmc->primaryMasterContract->details)->sum('order_value') ?? 0,
                    ];
                })

            ];
        });

    }

}
