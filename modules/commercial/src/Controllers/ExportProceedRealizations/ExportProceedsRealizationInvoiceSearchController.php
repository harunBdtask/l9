<?php


namespace SkylarkSoft\GoRMG\Commercial\Controllers\ExportProceedRealizations;

use SkylarkSoft\GoRMG\Commercial\Constants\CommercialConstant;
use SkylarkSoft\GoRMG\Commercial\Models\DocumentSubmission;
use SkylarkSoft\GoRMG\Commercial\Models\Exports\ExportProceedsRealization;

class ExportProceedsRealizationInvoiceSearchController
{
    public function __invoke(): \Illuminate\Http\JsonResponse
    {
        $request = request()->all();
        $buyer_id = $request['buyerId'] ?? null;
        $search_by = $request['search_by'] ?? null; // bill no | invoice no
        $search_query = $request['bill_no'] ?? null; // bill no value | invoice no value
        $invoices = [];
        // Already created IDs
        $excluded_ids = ExportProceedsRealization::query()->pluck('document_submission_id')->toArray();
        $document_submissions = DocumentSubmission::query()
            ->when(($excluded_ids && is_array($excluded_ids) && count($excluded_ids)), function ($query) use ($excluded_ids) {
                $query->whereNotIn('id', $excluded_ids);
            })
            ->when($buyer_id, function ($query) use ($buyer_id) {
                $query->where('buyer_id', $buyer_id);
            })
            ->when(($search_by && $search_query), function ($query) use ($search_by, $search_query) {
                $query->where('bank_ref_bill', $search_query);
            })
            ->get();
        $sl = 0;
        foreach ($document_submissions as $key => $documentSubmission) {
            $lc_sc_no = $documentSubmission->invoices->count() ? ($documentSubmission->invoices->first()->exportLc ? $documentSubmission->invoices->first()->exportLc->lc_number : ($documentSubmission->invoices->first()->salesContract ? $documentSubmission->invoices->first()->salesContract->contract_number : null)) : null;
            $export_lc_id = $documentSubmission->invoices->count() ? ($documentSubmission->invoices->first()->exportLc ? $documentSubmission->invoices->first()->export_lc_id : null) : null;
            $sales_contract_id = $documentSubmission->invoices->count() ? ($documentSubmission->invoices->first()->salesContract ? $documentSubmission->invoices->first()->sales_contract_id : null) : null;
            $beneficiary_id = $documentSubmission->factory_id ?? null;
            $beneficiary = $documentSubmission->factory->factory_name ?? null;
            $currency_id = $documentSubmission->currency_id;
            $currency = $documentSubmission->currency->currency_name ?? null;
            $negotiated_amount = $documentSubmission->transactions->count() ? $documentSubmission->transactions->sum('domestic_currency') : 0;
            $bill_invoice_amount = $documentSubmission->invoices->count() ? $documentSubmission->invoices->sum('net_inv_value') : 0;
            $bill_invoice_date = $documentSubmission->bank_ref_date ?? null;
            $deduction = [];
            $distribution = [];
            if ($documentSubmission->transactions->count()) {
                foreach ($documentSubmission->transactions as $transaction) {
                    $distribution[] = [
                        'document_submission_transaction_id' => $transaction->id,
                        'account_head_id' => $transaction->account_head_id,
                        'account_head' => $transaction->accountHead->name,
                        'ac_loan_no' => $transaction->account_head_id,
                        'document_currency' => $transaction->lc_sc_currency,
                        'conversion_rate' => $transaction->conversion_rate,
                        'domestic_currency' => $transaction->domestic_currency,
                        'status' => CommercialConstant::ExportProceedDistributionStatus,
                    ];
                }
            }
            $invoices[] = [
                'sl' => ++$sl,
                'document_submission_id' => $documentSubmission->id,
                'bill_no' => $documentSubmission->bank_ref_bill,
                'bill' => 'Bill',
                'lc_sc_no' => $lc_sc_no,
                'export_lc_id' => $export_lc_id,
                'sales_contract_id' => $sales_contract_id,
                'currency_id' => $currency_id,
                'currency' => $currency,
                'buyer_id' => $documentSubmission->buyer_id,
                'buyer' => $documentSubmission->buyer->name ?? null,
                'beneficiary_id' => $beneficiary_id,
                'beneficiary' => $beneficiary,
                'negotiated_amount' => $negotiated_amount,
                'bill_invoice_amount' => $bill_invoice_amount,
                'bill_invoice_date' => $bill_invoice_date,
                'deduction' => $deduction,
                'distribution' => $distribution,
            ];
        }

        return response()->json($invoices);
    }
}
