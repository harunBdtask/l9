<?php

namespace SkylarkSoft\GoRMG\Commercial\DTO;

use SkylarkSoft\GoRMG\Commercial\Models\Exports\CommercialRealization;
use SkylarkSoft\GoRMG\Commercial\Requests\CommercialRealizationRequest;

class CommercialRealizationInvoiceDTO
{
    /**
     * @var CommercialRealizationRequest $request
     */
    private $request;

    /**
     * @var CommercialRealization $commercialRealization
     */
    private $commercialRealization;

    /**
     * Constructor for CommercialRealizationInvoiceDTO
     * 
     * @param CommercialRealizationRequest $request
     */
    public function __construct(CommercialRealization $commercialRealization, CommercialRealizationRequest $request)
    {
        $this->commercialRealization = $commercialRealization;
        $this->request = $request;
    }

    public function format(): array
    {
        $data = [];
        $commercial_realization_id = $this->commercialRealization->id;
        $realization_date = $this->commercialRealization->realization_date;
        $document_submission_id = $this->commercialRealization->document_submission_id;
        $dbp_type = $this->commercialRealization->dbp_type;
        $bank_ref_bill = $this->commercialRealization->bank_ref_bill;
        $buyer_id = $this->commercialRealization->buyer_id;
        $factory_id = $this->commercialRealization->factory_id;
        $userId = userId();

        foreach($this->request->document_submission_invoice_id as $key => $document_submission_invoice_id)
        {
            $data[$key] = [
                'commercial_realization_id' => $commercial_realization_id,
                'realization_date' => $realization_date,
                'document_submission_id' => $document_submission_id,
                'dbp_type' => $dbp_type,
                'bank_ref_bill' => $bank_ref_bill,
                'buyer_id' => $buyer_id,
                'document_submission_invoice_id' => $this->request->document_submission_invoice_id[$key],
                'export_lc_id' => $this->request->export_lc_id[$key],
                'sales_contract_id' => $this->request->sales_contract_id[$key],
                'export_invoice_id' => $this->request->export_invoice_id[$key],
                'invoice_date' => $this->request->invoice_date[$key],
                'net_invoice_value' => $this->request->net_invoice_value[$key],
                'document_submission_date' => $this->request->document_submission_date[$key],
                'submission_value' => $this->request->submission_value[$key],
                'realized_value' => $this->request->realized_value[$key],
                'short_realized_value' => $this->request->short_realized_value[$key],
                'due_realized_value' => $this->request->due_realized_value[$key],
                'factory_id' => $factory_id,
                'created_by' => $userId,
                'updated_by' => $userId,
            ];
        }

        return $data;
    }
}