<?php


namespace SkylarkSoft\GoRMG\Commercial\Controllers;

use App\Constants\ApplicationConstant;
use App\Http\Controllers\Controller;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use SkylarkSoft\GoRMG\Commercial\Models\DocumentSubmission;
use SkylarkSoft\GoRMG\Commercial\Models\DocumentSubmissionInvoice;
use SkylarkSoft\GoRMG\Commercial\Models\DocumentSubmissionTransaction;
use SkylarkSoft\GoRMG\Commercial\Models\ExportInvoice;
use SkylarkSoft\GoRMG\Commercial\Models\ExportLC;
use SkylarkSoft\GoRMG\Commercial\Models\SalesContract;
use SkylarkSoft\GoRMG\Commercial\Requests\DocumentSubmissionRequest;
use SkylarkSoft\GoRMG\Merchandising\Models\PurchaseOrder;
use SkylarkSoft\GoRMG\SystemSettings\Models\Currency;
use Symfony\Component\HttpFoundation\Response;

class DocumentSubmissionController extends Controller
{
    public function index()
    {
        $document_submissions = DocumentSubmission::query()->latest()->paginate();

        return view('commercial::document-submission.index', compact('document_submissions'));
    }

    public function create()
    {
        return view('commercial::document-submission.create');
    }

    public function lcSearch(Request $request): JsonResponse
    {
        $lc_data = [];
        $factory_id = $request->factory_id ?? null;
        $buyer_id = $request->buyer_id ?? null;
        if (! $factory_id || ! $buyer_id) {
            return response()->json($lc_data);
        }
        $search_by = $request->search_by ?? null;
        $lc_sc_no = $request->lc_sc_no ?? null;
        $invoice_no = $request->invoice_no ?? null;
        $ids = [];
        if ($lc_sc_no) {
            $export_lc = ExportLC::query()->where('lc_number', $lc_sc_no)->first();
            $sales_contract = SalesContract::query()->where('contract_number', $lc_sc_no)->first();
            $export_lc_related_ids = ($export_lc && $export_lc->exportInvoice) ? $export_lc->exportInvoice->pluck('id')->toArray() : [];
            $sales_contract_related_ids = ($sales_contract && $sales_contract->exportInvoice) ? $sales_contract->exportInvoice->pluck('id')->toArray() : [];
            $ids = array_unique(array_merge($export_lc_related_ids, $sales_contract_related_ids));
        }
        $excluded_ids = DocumentSubmissionInvoice::all()->pluck('export_invoice_id');

        $export_invoices = ExportInvoice::query()
            ->when(($excluded_ids && is_array($excluded_ids) && count($excluded_ids)), function ($query) use ($excluded_ids) {
                $query->whereNotIn('id', $excluded_ids);
            })
            ->when(($factory_id && ! $buyer_id && ! $invoice_no && ! $lc_sc_no), function ($query) use ($factory_id) {
                $query->where('beneficiary_id', $factory_id);
            })
            ->when($buyer_id, function ($query) use ($buyer_id) {
                $query->where('buyer_id', $buyer_id);
            })
            ->when($invoice_no, function ($query) use ($invoice_no) {
                $query->where('invoice_no', $invoice_no);
            })
            ->when(($ids && is_array($ids) && count($ids) > 0), function ($query) use ($ids) {
                $query->whereIn('id', $ids);
            })
            ->get();
        $sl = 0;
        foreach ($export_invoices as $key => $export_invoice) {
            $lc_sc_no = $export_invoice->export_lc_id ? $export_invoice->exportLc->lc_number : ($export_invoice->sales_contract_id ? $export_invoice->salesContract->contract_number : null);
            $lc_sc_unique_id = $export_invoice->export_lc_id ? $export_invoice->exportLc->unique_id : ($export_invoice->sales_contract_id ? $export_invoice->salesContract->unique_id : null);
            $currency_id = $export_invoice->export_lc_id ? $export_invoice->exportLc->currency_id : ($export_invoice->sales_contract_id ? $export_invoice->salesContract->currency_id : null);
            $currency = $currency_id ? Currency::query()->findOrFail($currency_id)->currency_name : null;
            $bl_no = $export_invoice->shippingInformation ? $export_invoice->shippingInformation->bl_cargo_no : null;
            $po_ids = $export_invoice->details->pluck('po_id');
            $order_numbers = $export_invoice->details->implode('purchaseOrders.po_no', ',');
            $lc_data[] = [
                'sl' => ++$sl,
                'export_invoice_id' => $export_invoice->id,
                'invoice_no' => $export_invoice->invoice_no,
                'invoice_date' => $export_invoice->invoice_date,
                'lc_sc_unique_id' => $lc_sc_unique_id,
                'lc_sc_no' => $lc_sc_no,
                'buyer_id' => $export_invoice->buyer_id,
                'buyer' => $export_invoice->buyer->name,
                'currency_id' => $currency_id,
                'currency' => $currency,
                'uniq_id' => $export_invoice->uniq_id,
                'export_lc_id' => $export_invoice->export_lc_id,
                'sales_contract_id' => $export_invoice->sales_contract_id,
                'net_inv_qty' => round($export_invoice->details->sum('current_invoice_qty')),
                'net_inv_value' => round($export_invoice->details->sum('current_invoice_value'), 2),
                'bl_no' => $bl_no,
                'po_ids' => $po_ids,
                'order_numbers' => $order_numbers,
                'lien_bank_id' => $export_invoice->lien_bank_id,
                'lien_bank' => $export_invoice->lienBank->name,
            ];
        }

        return response()->json($lc_data);
    }

    public function store(DocumentSubmissionRequest $request): JsonResponse
    {
        $documentSubmissionData = $request->input('documentSubmission');
        $invoiceDetails = $request->input('invoiceDetails');
        $transactionDetails = $request->input('transactionDetails');

        try {
            \DB::beginTransaction();
            $documentSubmission = new DocumentSubmission($documentSubmissionData);
            $documentSubmission->save();
            $documentSubmission->invoices()->createMany($invoiceDetails);
            $documentSubmission->transactions()->createMany($transactionDetails);
            \DB::commit();

            return response()->json(['message' => ApplicationConstant::S_STORED]);
        } catch (\Throwable $e) {
            return response()->json(
                ['message' => ApplicationConstant::SOMETHING_WENT_WRONG, 'errMsg' => $e->getMessage()],
                Response::HTTP_BAD_REQUEST
            );
        }
    }

    public function update(DocumentSubmission $documentSubmission, DocumentSubmissionRequest $request): JsonResponse
    {
        $documentSubmissionData = $request->input('documentSubmission');
        $invoiceDetails = $request->input('invoiceDetails');
        $transactionDetails = $request->input('transactionDetails');

        try {
            \DB::beginTransaction();
            $documentSubmission->update($documentSubmissionData);
            $this->saveAndUpdateInvoiceDetails($invoiceDetails, $documentSubmission);
            $this->saveAndUpdateTransactionDetails($transactionDetails, $documentSubmission);
            \DB::commit();

            return response()->json(['message' => ApplicationConstant::S_UPDATED]);
        } catch (\Throwable $e) {
            return response()->json(
                ['message' => ApplicationConstant::SOMETHING_WENT_WRONG, 'errMsg' => $e->getMessage()],
                Response::HTTP_BAD_REQUEST
            );
        }
    }

    public function show(DocumentSubmission $documentSubmission): JsonResponse
    {
        $documentSubmission->load([
            'invoices.exportLc:id,lc_number',
            'invoices.salesContract:id,contract_number',
            'transactions',
            'lienBank:id,name,address,contact_person',
            'currency:id,currency_name',
        ]);
        $invoices = [];
        foreach ($documentSubmission->invoices as $inv_key => $invoice) {
            $lc_sc_no = $invoice->exportLc ? $invoice->exportLc->lc_number : ($invoice->salesContract ? $invoice->salesContract->contract_number : null);
            $lc_sc_unique_id = $invoice->exportLc ? $invoice->exportLc->unique_id : ($invoice->salesContract ? $invoice->salesContract->unique_id : null);
            $po_ids = $invoice->po_ids ?? null;
            $order_numbers = $po_ids ?
                PurchaseOrder::query()
                    ->select('po_no')
                    ->whereIn('id', $invoice->po_ids)
                    ->get()
                    ->implode('po_no', ',')
                : null;
            $invoices[] = [
                'document_submission_id' => $invoice->document_submission_id ?? null,
                'buyer_id' => $invoice->buyer_id ?? null,
                'export_lc_id' => $invoice->export_lc_id ?? null,
                'sales_contract_id' => $invoice->sales_contract_id ?? null,
                'lc_sc_unique_id' => $lc_sc_unique_id,
                'lc_sc_no' => $lc_sc_no,
                'export_invoice_id' => $invoice->export_invoice_id ?? null,
                'invoice_no' => $invoice->exportInvoice->invoice_no ?? null,
                'bl_no' => $invoice->bl_no ?? null,
                'invoice_date' => $invoice->invoice_date ?? null,
                'net_inv_value' => $invoice->net_inv_value ?? null,
                'po_ids' => $po_ids ?? null,
                'order_numbers' => $order_numbers,
                'factory_id' => $invoice->factory_id ?? null,
                'id' => $invoice->id,
                'currency_id' => $documentSubmission->currency_id,
                'currency' => $documentSubmission->currency->currency_name,
                'uniq_id' => $documentSubmission->uniq_id,
                'lien_bank_id' => $documentSubmission->lien_bank_id ?? null,
                'lien_bank' => $documentSubmission->lienBank->name ?? null,
            ];
        }
        $transactions = [];
        if ($documentSubmission->transactions->count()) {
            foreach ($documentSubmission->transactions as $transaction) {
                $transactions[] = [
                    'id' => $transaction->id,
                    'document_submission_id' => $transaction->document_submission_id,
                    'account_head_id' => $transaction->account_head_id,
                    'account_head' => $transaction->accountHead->name,
                    'ac_loan_no' => $transaction->ac_loan_no,
                    'domestic_currency' => $transaction->domestic_currency,
                    'conversion_rate' => $transaction->conversion_rate,
                    'lc_sc_currency' => $transaction->lc_sc_currency,
                    'factory_id' => $transaction->factory_id,
                ];
            }
        }
        $lc_sc_no = $documentSubmission->invoices->count() ? ($documentSubmission->invoices->first()->exportLc ? $documentSubmission->invoices->first()->exportLc->lc_number : ($documentSubmission->invoices->first()->salesContract ? $documentSubmission->invoices->first()->salesContract->contract_number : null)) : null;
        $submissions = [
            "id" => $documentSubmission->id,
            "factory_id" => $documentSubmission->factory_id ?? null,
            "buyer_id" => $documentSubmission->buyer_id ?? null,
            "lc_sc_no" => $lc_sc_no,
            "submission_date" => $documentSubmission->submission_date ?? null,
            "submitted_to" => $documentSubmission->submitted_to ?? null,
            "bank_ref_bill" => $documentSubmission->bank_ref_bill ?? null,
            "bank_ref_date" => $documentSubmission->bank_ref_date ?? null,
            "submission_type" => $documentSubmission->submission_type ?? null,
            "dbp_type" => $documentSubmission->dbp_type ?? null,
            "negotiation_date" => $documentSubmission->negotiation_date ?? null,
            "days_to_realize" => $documentSubmission->days_to_realize ?? null,
            "possible_reali_date" => $documentSubmission->possible_reali_date ?? null,
            "courier_receipt_no" => $documentSubmission->courier_receipt_no ?? null,
            "courier_company" => $documentSubmission->courier_company ?? null,
            "gsp_courier_date" => $documentSubmission->gsp_courier_date ?? null,
            "lc_sc_currency" => $documentSubmission->currency->currency_name,
            "lc_sc_currency_id" => $documentSubmission->currency_id,
            "bank_to_bank_cour_no" => $documentSubmission->bank_to_bank_cour_no ?? null,
            "bank_to_bank_cour_date" => $documentSubmission->bank_to_bank_cour_date ?? null,
            "lien_bank" => $documentSubmission->lienBank->name ?? null,
            "lien_bank_id" => $documentSubmission->lien_bank_id ?? null,
            "remarks" => $documentSubmission->remarks ?? null,
            "invoices" => $invoices,
            "transactions" => $transactions,
        ];

        return response()->json($submissions);
    }

    public function delete(DocumentSubmission $documentSubmission)
    {
        try {
            \DB::beginTransaction();
            $documentSubmission->invoices()->delete();
            $documentSubmission->transactions()->delete();
            $documentSubmission->delete();
            \DB::commit();
            Session::flash('success', 'Data Deleted Successfully');
        } catch (\Exception $e) {
            \DB::rollBack();
            Session::flash('error', $e->getMessage());
        }

        return redirect('/commercial/document-submission');
    }

    public function deleteTransaction(DocumentSubmissionTransaction $documentTransaction)
    {
        try {
            \DB::beginTransaction();
            $documentTransaction->delete();
            \DB::commit();

            return response()->json(['message' => ApplicationConstant::S_DELETED]);
        } catch (\Exception $e) {
            \DB::rollBack();

            return response()->json([
                'message' => ApplicationConstant::SOMETHING_WENT_WRONG,
                'error' => $e->getMessage(),
            ]);
        }
    }

    public function view(DocumentSubmission $documentSubmission)
    {
        $id = $documentSubmission->id;
        $documentSubmission->load(
            'invoices.exportLc:id,lc_number,lc_date',
            'invoices.salesContract:id,contract_number',
            'invoices.exportInvoice:id,invoice_no,invoice_date'
        );
        $invoices = [];
        foreach ($documentSubmission->invoices as $invoice) {
            $invoices[] = $this->viewFormatter($invoice);
        }

        return view('commercial::document-submission.Report.view', compact('invoices', 'id'));
    }

    public function print(DocumentSubmission $documentSubmission)
    {
        $documentSubmission->load(
            'invoices.exportLc:id,lc_number,lc_date',
            'invoices.salesContract:id,contract_number',
            'invoices.exportInvoice:id,invoice_no,invoice_date',
            'lienBank:id,name,address,contact_person'
        );
        $invoices = [];
        foreach ($documentSubmission->invoices as $invoice) {
            $invoices[] = $this->viewFormatter($invoice);
        }

        return view('commercial::document-submission.Report.print', compact('invoices', 'documentSubmission'));
    }

    /**
     * @param $invoiceDetails
     * @param DocumentSubmission $documentSubmission
     * @return mixed
     */
    private function saveAndUpdateInvoiceDetails($invoiceDetails, DocumentSubmission $documentSubmission)
    {
        foreach ($invoiceDetails as $invoiceDetail) {
            if (isset($invoiceDetail['id'])) {
                $documentSubmission->invoices()->find($invoiceDetail['id'])->update($invoiceDetail);

                continue;
            }

            $documentSubmission->invoices()->create($invoiceDetail);
        }
    }

    /**
     * @param $transactionDetails
     * @param DocumentSubmission $documentSubmission
     */
    private function saveAndUpdateTransactionDetails($transactionDetails, DocumentSubmission $documentSubmission): void
    {
        foreach ($transactionDetails as $detail) {
            if (isset($detail['id'])) {
                $documentSubmission->transactions()->find($detail['id'])->update($detail);

                continue;
            }

            $documentSubmission->transactions()->create($detail);
        }
    }

    private function viewFormatter($invoice): array
    {
        $bl_date = $invoice->exportInvoice ? ($invoice->exportInvoice->shippingInformation ? $invoice->exportInvoice->shippingInformation->bl_cargo_date : null) : null;
        $lc_sc_no = $invoice->exportLc ? $invoice->exportLc->lc_number : ($invoice->salesContract ? $invoice->salesContract->contract_number : null);
        $lc_sc_date = $invoice->exportLc ? $invoice->exportLc->lc_date : ($invoice->salesContract ? $invoice->salesContract->contract_date : null);
        $po_ids = $invoice->po_ids ?? null;
        $order_numbers = $po_ids ?
            PurchaseOrder::query()
                ->select('po_no')
                ->whereIn('id', $invoice->po_ids)
                ->get()
                ->implode('po_no', ',')
            : null;

        return [
            'lc_sc_no' => $lc_sc_no,
            'lc_sc_date' => $lc_sc_date,
            'order_numbers' => $order_numbers,
            'invoice_no' => $invoice->exportInvoice->invoice_no ?? null,
            'invoice_date' => $invoice->invoice_date ?? null,
            'value' => $invoice->net_inv_value ?? null,
            'bl_no' => $invoice->bl_no ?? null,
            'bl_date' => $bl_date,
        ];
    }

    public function getBankRefs(Request $request)
    {
        try {
            $dbpType = $request->dbp_type ?? null;
            $data = DocumentSubmission::query()
                ->select('id', 'bank_ref_bill')
                ->when($dbpType, function ($query) use($dbpType) {
                    $query->where('dbp_type', $dbpType);
                })
                ->get()
                ->map(function($item) {
                    return [
                        'id' => $item->id,
                        'text' => $item->bank_ref_bill,
                        'bank_ref_bill' => $item->bank_ref_bill,
                    ];
                });
            $status = Response::HTTP_OK;
            $message = \SUCCESS_MSG;
        } catch (Exception $e) {
            $status = Response::HTTP_INTERNAL_SERVER_ERROR;
            $message = \SOMETHING_WENT_WRONG;
            $error = $e->getMessage();
        }

        return response()->json([
            'data' => $data ?? null,
            'status' => $status ?? null,
            'message' => $message ?? null,
            'error' => $error ?? null,
        ], $status);
    }
}
