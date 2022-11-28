<?php

namespace SkylarkSoft\GoRMG\Commercial\Services\Commercial;

use SkylarkSoft\GoRMG\Commercial\Models\SalesContract;

class PrimaryContractService
{
    public  function formatPrimaryContract($contracts)
    {
        $listData = array(
            ['title' => 'System Id','value' => $contracts->unique_id],
            ['title' => 'Export Contact No','value' => $contracts->ex_contract_number],
            ['title' => 'Issue Date','value' => ($contracts->ex_cont_issue_date?date('d-m-Y', strtotime($contracts->ex_cont_issue_date)):'')],
            ['title' => 'Buyer Name','value' => $contracts->buyingAgent->buying_agent_name],
            ['title' => 'Buyer Address','value' => $contracts->buyingAgent->address],
            ['title' => 'Beneficiary Name','value' => $contracts->beneficiary->factory_name.' '.$contracts->beneficiary->factory_address],
            ['title' => 'Description Of Goods','value' => implode(', ', $contracts->details->pluck('description')->toArray())],
            ['title' => 'Order No/P.O','value' => implode(', ', $contracts->details->pluck('po')->toArray())],
            ['title' => 'Style No','value' => implode(', ', $contracts->details->pluck('style_order')->toArray())],
            ['title' => 'Quantity','value' => $contracts->details->sum('order_qty')],
            ['title' => 'Value','value' => $contracts->details->sum('order_value')],
            ['title' => 'Tolerance','value' => $contracts->tolerance],
            ['title' => 'Sales Terms','value' => $contracts->document_terms],
            ['title' => 'Draft','value' => $contracts->draft],
            ['title' => 'Shipment Date','value' => ($contracts->shipment_date?date('d-m-Y', strtotime($contracts->shipment_date)):'')],
            ['title' => 'Date Of Place of Expiry','value' => ($contracts->expiry_date?date('d-m-Y', strtotime($contracts->expiry_date)):'')],
            ['title' => 'Documents Required','value' => $contracts->document_required],
            ['title' => 'Presentation Period','value' => $contracts->presentation_period],
            ['title' => 'Remarks','value' => $contracts->remarks],
            ['title' => 'Lien Bank','value' => $contracts->lienBank->name],
            ['title' => 'Lien Bank Address','value' => $contracts->lienBank->address],
            ['title' => 'Contract Date','value' => ($contracts->contract_date?date('d-m-Y', strtotime($contracts->contract_date)):'')],
        );
        return $listData;
    }
}