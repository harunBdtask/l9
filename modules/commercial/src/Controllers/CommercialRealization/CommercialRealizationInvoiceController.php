<?php

namespace SkylarkSoft\GoRMG\Commercial\Controllers\CommercialRealization;

use App\Http\Controllers\Controller;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use SkylarkSoft\GoRMG\Commercial\Models\DocumentSubmission;
use SkylarkSoft\GoRMG\Commercial\Models\Exports\CommercialRealizationInvoice;
use SkylarkSoft\GoRMG\Commercial\Models\PrimaryMasterContract\PrimaryMasterContract;
use Symfony\Component\HttpFoundation\Response;

class CommercialRealizationInvoiceController extends Controller
{

    public function create(DocumentSubmission $documentSubmission)
    {
        try {
            $data['document_submission'] = $documentSubmission;
            $data['document_submission_invoices'] = [];
            $primaryContractNos = [];
            foreach ($documentSubmission->invoices as $key => $invoice) {
                $primary_contract_id = $invoice->sales_contract_id ? $invoice->salesContract->primary_contract_id : ($invoice->export_lc_id ? $invoice->exportLc->primary_contract_id : null);
                $primaryContractNo = $primary_contract_id ? PrimaryMasterContract::find($primary_contract_id)->ex_contract_number : null;
                
                if ($primaryContractNo) {
                    $primaryContractNos[] = $primaryContractNo;
                }
                $pervRealizedQuery = CommercialRealizationInvoice::query()->where('document_submission_invoice_id', $invoice->id);
                $pervShortRealizedQuery = clone $pervRealizedQuery;
                $prev_realized_value = $pervRealizedQuery->sum('realized_value') + $pervShortRealizedQuery->sum('short_realized_value');
                $realized_value = 0;
                $short_realized_value = 0;
                $due_realized_value = $invoice->net_inv_value - $prev_realized_value - $realized_value - $short_realized_value;
                $data['document_submission_invoices'][$key] = [
                    'document_submission_invoice_id' => $invoice->id,
                    'export_lc_id' => $invoice->export_lc_id,
                    'export_lc_no' => $invoice->export_lc_id ? $invoice->exportLc->lc_number : null,
                    'sales_contract_id' => $invoice->sales_contract_id,
                    'sales_contract_no' => $invoice->sales_contract_id ? $invoice->salesContract->contract_number : null,
                    'invoice_no' => $invoice->exportInvoice->invoice_no,
                    'export_invoice_id' => $invoice->export_invoice_id,
                    'primary_contract_id' => $primary_contract_id,
                    'primary_contract_no' => $primaryContractNo,
                    'invoice_date' => $invoice->invoice_date,
                    'prev_realized_value' => $prev_realized_value,
                    'net_invoice_value' => $invoice->net_inv_value,
                    'document_submission_date' => $documentSubmission->submission_date,
                    'submission_value' => $invoice->net_inv_value,
                    'realized_value' => $realized_value,
                    'short_realized_value' => $short_realized_value,
                    'due_realized_value' => $due_realized_value,
                ];
            }
            $buyerInfos = [
                'buyer' => $documentSubmission->buyer->name,
                'primary_contract_no' => ($primaryContractNos && \is_array($primaryContractNos) && count($primaryContractNos)) ? \implode(', ', $primaryContractNos) : '',
                'sales_contract_no' => $documentSubmission->invoices->pluck('salesContract.contract_number')->implode(', '),
                'lc_no' => $documentSubmission->invoices->pluck('exportLc.lc_number')->implode(', ')
            ];

            $view =view('commercial::commercial_realization.invoice_form', $data)->render();
            $status = Response::HTTP_OK;
            $message = \S_DELETE_MSG;
        } catch (Exception $e) {
            $status = Response::HTTP_INTERNAL_SERVER_ERROR;
            $message = \SOMETHING_WENT_WRONG;
            $error = $e->getMessage();
        }

        return \response()->json([
            'view' => $view ?? null,
            'data' => $data ?? null,
            'buyer_info' => $buyerInfos ?? null,
            'status' => $status ?? null,
            'message' => $message ?? null,
            'error' => $error ?? null,
        ], $status);
    }

    /**
     * Delete CommercialRealizationInvoice Data
     * 
     * @param CommercialRealizationInvoice $commercialRealizationInvoice
     * @param JsonResponse
     */
    public function destroy(CommercialRealizationInvoice $commercialRealizationInvoice): JsonResponse
    {
        try {
            DB::beginTransaction();
            $commercialRealizationInvoice->delete();
            DB::commit();
            
            $data = $commercialRealizationInvoice;
            $status = Response::HTTP_OK;
            $message = \S_DELETE_MSG;
        } catch (Exception $e) {
            DB::rollback();

            $status = Response::HTTP_INTERNAL_SERVER_ERROR;
            $message = \SOMETHING_WENT_WRONG;
            $error = $e->getMessage();
        }
        
        return \response()->json([
            'data' => $data ?? null,
            'status' => $status ?? null,
            'message' => $message ?? null,
            'error' => $error ?? null,
        ], $status);
    }
}