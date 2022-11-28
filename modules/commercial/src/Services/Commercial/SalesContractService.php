<?php

namespace SkylarkSoft\GoRMG\Commercial\Services\Commercial;

use SkylarkSoft\GoRMG\Commercial\Models\SalesContract;

class SalesContractService
{
    public static function salesContractData($salesContract)
    {
        $salesContract = $salesContract->load('consignee:id,name,address_1,address_2', 'details', 'details.po.poDetails.garmentItem',
            'details.order','details.order.assignFactory:id,name,address', 'factory:id,factory_name,factory_address', 'lienBank:id,name,address');
        return self::formatData($salesContract);
    }

    public static function formatData($salesContract)
    {
        $data = [];
        $data['id'] = $salesContract->id ?? '';
        $data['remarks'] = $salesContract->remarks ?? '';
        $data['contract_date'] = $salesContract->contract_date ?? '';
        $data['contract_number'] = $salesContract->contract_number ?? '';
        $data['consignee'] = $salesContract->consignee->name ?? '';
        $data['consignee_address'] = $salesContract->consignee->address_1 ?? '';
        $data['lien_bank'] = $salesContract->lienBank->name ?? '';
        $data['lien_bank_address'] = $salesContract->lienBank->address ?? '';
        $data['beneficiary'] = $salesContract->factory->factory_name ?? '';
        $data['beneficiary_address'] = $salesContract->factory->factory_address ?? '';
        $data['contract_no'] = $salesContract->contract_number ?? '';
        $data['second_beneficiary'] = $salesContract->details->pluck('order.assignFactory.name')->unique()->whereNotNull()->first() ?? ' ';
        $data['second_beneficiary_address'] = $salesContract->details->pluck('order.assignFactory.address')->unique()->whereNotNull()->first() ?? ' ';
        $data['details'] = $salesContract->details ? collect($salesContract->details)->map(function ($item) {
            return [
                'style' => $item->order->style_name ?? '',
                'po_no' => $item->po->po_no ?? '',
                'description' => collect($item->po->poDetails)->pluck('garmentItem.name')->implode(', ') ?? '',
                'po_qty' => (float) $item->attach_qty,
                'rate' => (float)  $item->rate,
                'total_amount' => (float) $item->attach_qty * (float) $item->rate,
                'shipment_date' => $item->po->ex_factory_date ?? ''
            ];
        }) : [];
        return $data;
    }

}