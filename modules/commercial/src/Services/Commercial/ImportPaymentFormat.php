<?php

namespace SkylarkSoft\GoRMG\Commercial\Services\Commercial;

class ImportPaymentFormat
{
    public static function searchFormat($data, $itemId, $factoryId, $supplierId): array
    {
        return [
            'import_document_acceptance_id' => $data['id'],
            'item_id' => $itemId,
            'supplier_id' => $supplierId,
            'supplier' => $data->supplier->name,
            'lc_number' => $data->bToBMarginLC->lc_number,
            'lc_value' => $data->bToBMarginLC->lc_value,
            'invoice_no' => $data['invoice_number'],
            'invoice_date' => $data['invoice_date'],
            'bank_reference' => $data['bank_ref'],
            'document_amount' => $data['document_value'],
            'factory_id' => $factoryId,
            'factory' => $data->factory->factory_name,
            'file_no' => $data->shippingInfo->internal_file_no,
            'invoice_value' => collect($data['piInfos'])->sum('current_acceptance_value'),
            'currency_id' => $data['currency_id'],
            'currency' => $data->currency->currency_name,
            'shipment_date' => $data['shipment_date'],
            'bank_acceptance_date' => $data['bank_acc_date'],
            'bl_cargo_date' => $data['shippingInfo']->bl_cargo_date,
            'issuing_bank_id' => $data['lien_bank_id'],
            'issuing_bank' => $data->lienBank->name,
            'maturity_from' => $data['bToBMarginLC']->maturity_from,
            'maturity_date' => $data->shippingInfo->maturity_date,
        ];
    }

    public static function editFormat($rawData): array
    {
        $data = $rawData->importDocumentAcceptance;

        return [
            'id' => $rawData->id,
            'import_document_acceptance_id' => $data['id'],
            'item_id' => isset($data->piInfos[0]) ? $data->piInfos[0]->item_id : null,
            'supplier_id' => $data->supplier->id,
            'supplier' => $data->supplier->name,
            'lc_number' => $data->bToBMarginLC->lc_number,
            'lc_value' => $data->bToBMarginLC->lc_value,
            'invoice_no' => $data['invoice_number'],
            'invoice_date' => $data['invoice_date'],
            'bank_reference' => $data['bank_ref'],
            'document_amount' => $data['document_value'],
            'factory_id' => $data->factory->id,
            'factory' => $data->factory->factory_name,
            'file_no' => $data->shippingInfo->internal_file_no,
            'invoice_value' => collect($data['piInfos'])->sum('current_acceptance_value'),
            'currency' => $data->currency->currency_name,
            'shipment_date' => $data['shipment_date'],
            'bank_acceptance_date' => $data['bank_acc_date'],
            'bl_cargo_date' => $data['shippingInfo']->bl_cargo_date,
            'issuing_bank_id' => $data['lien_bank_id'],
            'issuing_bank' => $data->lienBank->name,
            'maturity_from' => $data['bToBMarginLC']->maturity_from,
            'maturity_date' => $data->shippingInfo->maturity_date,
            'payment_date' => $rawData->payment_date,
            'payment_head_id' => $rawData->payment_head_id,
            'adj_source_id' => $rawData->adj_source_id,
            'conversion_rate' => $rawData->conversion_rate,
            'accepted_amount' => $rawData->accepted_amount,
            'currency_id' => $rawData->currency_id,
            'remarks' => $rawData->remarks,
        ];
    }
}
