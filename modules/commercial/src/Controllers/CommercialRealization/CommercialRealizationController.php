<?php

namespace SkylarkSoft\GoRMG\Commercial\Controllers\CommercialRealization;

use App\Http\Controllers\Controller;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Illuminate\View\View;
use SkylarkSoft\GoRMG\Commercial\Constants\CommercialConstant;
use SkylarkSoft\GoRMG\Commercial\DTO\CommercialRealizationInvoiceDTO;
use SkylarkSoft\GoRMG\Commercial\Models\DocumentSubmission;
use SkylarkSoft\GoRMG\Commercial\Models\Exports\CommercialRealization;
use SkylarkSoft\GoRMG\Commercial\Requests\CommercialRealizationRequest;
use Symfony\Component\HttpFoundation\Response;

class CommercialRealizationController extends Controller
{
    /**
     * Get Commercial Realization List
     * 
     * @return View
     */
    public function index(): View
    {
        $commercialRealizations = CommercialRealization::query()->orderBy('id', 'desc')->paginate();

        return view('commercial::commercial_realization.index', [
            'commercial_realizations' => $commercialRealizations
        ]);
    }

    /**
     * Get Commercial Realization Create Form
     * 
     * @return View
     */
    public function create(): View
    {
        $dbpTypes = CommercialConstant::DBP_TYPES;

        // return $documentSubmissionIds =  DocumentSubmission::query()
        //     ->pluck('bank_ref_bill', 'id');
        
        return view('commercial::commercial_realization.form', [
            'commercial_realization' => null,
            'dbp_types' => $dbpTypes,
            // 'documentSubmissionIds' => $documentSubmissionIds,
        ]);
    }

    /**
     * Store new CommercialRealization Data
     * 
     * @param CommercialRealizationRequest $request
     * @return JsonResponse
     */
    public function store(CommercialRealizationRequest $request): JsonResponse
    {
        try {
            DB::beginTransaction();
            $commercialRealizationData = $request->only([
                'realization_date',
                'document_submission_id',
                'dbp_type',
                'conversion_rate',
                'bank_ref_bill',
                'buyer_id',
                'factory_id'
            ]);
            $commercialRealization = new CommercialRealization();
            $commercialRealization->fill($commercialRealizationData);
            $commercialRealization->save();

            $commercialRealizationInvoiceData = (new CommercialRealizationInvoiceDTO($commercialRealization, $request))->format();
            $commercialRealization->commercialRealizationInvoices()->createMany($commercialRealizationInvoiceData);

            DB::commit();

            $data = $commercialRealization;
            $status = Response::HTTP_OK;
            $message = \S_SAVE_MSG;
        } catch (Exception $e) {
            DB::rollBack();

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

    /**
     * Get Commercial Realization View Page
     * 
     * @param CommercialRealization $commercialRealization 
     * @return View
     */
    public function show(CommercialRealization $commercialRealization): View
    {
        return view('commercial::commercial_realization.show', [
            'commercial_realization' => $commercialRealization
        ]);
    }

    /**
     * Get Commercial Realization Edit Form
     * 
     * @param CommercialRealization $commercialRealization 
     * @return View
     */
    public function edit(CommercialRealization $commercialRealization): View
    {
        $dbpTypes = CommercialConstant::DBP_TYPES;
        $documentSubmissionIds =  DocumentSubmission::query()
            ->where('id', $commercialRealization->document_submission_id)
            ->pluck('bank_ref_bill', 'id');
        $primaryContractNo = $commercialRealization->commercialRealizationInvoices->pluck('primaryContract.ex_contract_number')->implode(', ');
        $salesContractNo = $commercialRealization->commercialRealizationInvoices->pluck('salesContract.contract_number')->implode(', ');
        $lcNo = $commercialRealization->commercialRealizationInvoices->pluck('exportLc.lc_number')->implode(', ');
        
        return view('commercial::commercial_realization.form', [
            'commercial_realization' => $commercialRealization,
            'dbp_types' => $dbpTypes,
            'document_submission_ids' => $documentSubmissionIds,
            'primary_contract_no' => $primaryContractNo,
            'sales_contract_no' => $salesContractNo,
            'lc_no' => $lcNo,
        ]);
    }

    /**
     * Update existing CommercialRealization Data
     * 
     * @param CommercialRealization $commercialRealization
     * @param CommercialRealizationRequest $request
     * @return JsonResponse
     */
    public function update(CommercialRealization $commercialRealization, CommercialRealizationRequest $request): JsonResponse
    {
        try {
            DB::beginTransaction();

            $commercialRealizationData = $request->only([
                'realization_date',
                'document_submission_id',
                'dbp_type',
                'bank_ref_bill',
                'conversion_rate',
                'buyer_id',
                'factory_id'
            ]);
            $commercialRealization->commercialRealizationInvoices()->forceDelete();
            $commercialRealization->fill($commercialRealizationData);
            $commercialRealization->save();

            $commercialRealizationInvoiceData = (new CommercialRealizationInvoiceDTO($commercialRealization, $request))->format();
            $commercialRealization->commercialRealizationInvoices()->createMany($commercialRealizationInvoiceData);

            DB::commit();

            $data = $commercialRealization;
            $status = Response::HTTP_OK;
            $message = \S_UPDATE_MSG;
        } catch (Exception $e) {
            DB::rollBack();

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

    /**
     * Delete CommercialRealization Data
     * 
     * @param CommercialRealization $commercialRealization
     * @param RedirectResponse
     */
    public function destroy(CommercialRealization $commercialRealization): RedirectResponse
    {
        try {
            DB::beginTransaction();
            $commercialRealization->delete();
            DB::commit();
            Session::flash('success', 'Data Deleted Successfully!!');
        } catch (Exception $e) {
            DB::rollback();
            Session::flash('error', 'Something went wrong!');
        }
        return redirect()->back();
    }

    public function fetchData(Request $request)
    {
        try {
            $realization_type = $request->realization_type ?? null;
            $data = [];
            if ($realization_type) {
                $data = CommercialRealization::query()
                    ->where('dbp_type', $realization_type)
                    ->get()
                    ->map(function($item) {
                        $export_lc_ids = [];
                        $export_lcs = [];
                        $sales_contract_ids = [];
                        $sales_contracts = [];
                        $export_invoice_ids = [];
                        $export_invoices = [];
                        if ($item->documentSubmission && $item->documentSubmission->invoices->count()) {
                            $export_lc_ids = $item->documentSubmission->invoices->whereNotNull('export_lc_id')->pluck('export_lc_id')->toArray();
                            $export_lcs = $item->documentSubmission->invoices->whereNotNull('export_lc_id')->unique('export_lc_id')->pluck('exportLc.lc_number')->toArray();
                            $sales_contract_ids = $item->documentSubmission->invoices->whereNotNull('sales_contract_id')->pluck('sales_contract_id')->toArray();
                            $sales_contracts = $item->documentSubmission->invoices->whereNotNull('sales_contract_id')->unique('sales_contract_id')->pluck('salesContract.contract_number')->toArray();
                            $export_invoice_ids = $item->documentSubmission->invoices->whereNotNull('export_invoice_id')->pluck('export_invoice_id')->toArray();
                            $export_invoices = $item->documentSubmission->invoices->whereNotNull('export_invoice_id')->unique('export_invoice_id')->pluck('exportInvoice.invoice_no')->toArray();
                        }
                        $realized_value = $item->commercialRealizationInvoices->sum('realized_value');
                        return [
                            'id' => $item->id,
                            'text' => $item->bank_ref_bill,
                            'realization_date' => $item->realization_date,
                            'document_submission_id' => $item->document_submission_id,
                            'dbp_type' => $item->dbp_type,
                            'bank_ref_bill' => $item->bank_ref_bill,
                            'conversion_rate' => $item->conversion_rate,
                            'buyer_id' => $item->buyer_id,
                            'factory_id' => $item->factory_id,
                            'export_lc_id' => $export_lc_ids,
                            'export_lcs' => $export_lcs,
                            'sales_contract_id' => $sales_contract_ids,
                            'sales_contracts' => $sales_contracts,
                            'export_invoice_id' => $export_invoice_ids,
                            'export_invoices' => $export_invoices,
                            'realized_value' => $realized_value,
                        ];
                    });
            }

            $status = Response::HTTP_OK;
            $message = \S_UPDATE_MSG;
        } catch (Exception $e) {

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
