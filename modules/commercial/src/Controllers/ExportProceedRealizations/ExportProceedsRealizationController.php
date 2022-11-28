<?php

namespace SkylarkSoft\GoRMG\Commercial\Controllers\ExportProceedRealizations;

use App\Constants\ApplicationConstant;
use App\Http\Controllers\Controller;
use DB;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Session;
use SkylarkSoft\GoRMG\Commercial\Constants\CommercialConstant;
use SkylarkSoft\GoRMG\Commercial\Models\Exports\ExportProceedDeduction;
use SkylarkSoft\GoRMG\Commercial\Models\Exports\ExportProceedDistribution;
use SkylarkSoft\GoRMG\Commercial\Models\Exports\ExportProceedsRealization;

class ExportProceedsRealizationController extends Controller
{
    public function index()
    {
        $data = ExportProceedsRealization::query()->latest()->paginate(15);

        return view('commercial::export-proceds.export-proceed-list', [
            'datas' => $data,
        ]);
    }

    public function create()
    {
        return view('commercial::export-proceds.export-proceds-form');
    }

    public function store(Request $request)
    {
        $request->validate([
            'beneficiary_id' => 'required',
            'buyer_id' => 'required',
            'document_submission_id' => 'required',
            'deductions.*' => 'required',
            'distributions.*' => 'required',
        ], [
            'required' => 'Required field',
        ]);

        try {
            DB::beginTransaction();
            $realization = new ExportProceedsRealization($request->only([
                'beneficiary_id',
                'buyer_id',
                'document_submission_id',
                'export_lc_id',
                'sales_contract_id',
                'receive_date',
                'lc_sc_no',
                'currency_id',
                'bill_invoice_date',
                'bill_invoice_amount',
                'negotiated_amount',
                'document_currency',
                'domestic_currency',
            ]));
            $realization->save();

            $deductions = $request->deductions ?? [];
            $distributions = $request->distributions ?? [];

            if (is_array($distributions) && count($distributions) > 0) {
                foreach ($distributions as $key => $distribution) {
                    $distribution['status'] = CommercialConstant::ExportProceedDistributionStatus;
                    $distributions[$key] = $distribution;
                }
            }
            $realization->deductions()->createMany($deductions);
            $realization->distributions()->createMany($distributions);

            DB::commit();

            return response()->json([
                'status' => 200,
                'error' => null,
                'message' => ApplicationConstant::S_CREATED,
                'data' => $realization,
            ]);
        } catch (\Exception $exception) {
            DB::rollBack();

            return response()->json([
                'status' => 500,
                'error' => $exception->getMessage(),
                'message' => ApplicationConstant::SOMETHING_WENT_WRONG,
            ]);
        }
    }

    public function update(ExportProceedsRealization $realization, Request $request): JsonResponse
    {
        $request->validate([
            'beneficiary_id' => 'required',
            'buyer_id' => 'required',
            'document_submission_id' => 'required',
            'deductions.*' => 'required',
            'distributions.*' => 'required',
        ], [
            'required' => 'Required field',
        ]);

        try {
            DB::beginTransaction();
            $realization->update($request->only([
                'beneficiary_id',
                'buyer_id',
                'export_invoice_id',
                'receive_date',
                'lc_sc_no',
                'currency_id',
                'bill_invoice_date',
                'bill_invoice_amount',
                'negotiated_amount',
                'document_currency',
                'domestic_currency',
            ]));
            $deductions = $request->deductions ?? [];
            $distributions = $request->distributions ?? [];

            $realization->deductions()->forceDelete();
            $realization->distributions()->forceDelete();
            if (is_array($distributions) && count($distributions) > 0) {
                foreach ($distributions as $key => $distribution) {
                    $distribution['status'] = CommercialConstant::ExportProceedDistributionStatus;
                    $distributions[$key] = $distribution;
                }
            }
            $realization->deductions()->createMany($deductions);
            $realization->distributions()->createMany($distributions);

            DB::commit();

            return response()->json([
                'status' => 200,
                'error' => null,
                'message' => ApplicationConstant::S_UPDATED,
            ]);
        } catch (\Exception $exception) {
            DB::rollBack();

            return response()->json([
                'status' => 500,
                'error' => $exception->getMessage(),
                'message' => ApplicationConstant::SOMETHING_WENT_WRONG,
            ]);
        }
    }

    public function edit(ExportProceedsRealization $realization): JsonResponse
    {
        $deductions = [];
        $distributions = [];
        if ($realization->deductions) {
            foreach ($realization->deductions as $deduction_data) {
                $deductions[] = [
                    'id' => $deduction_data->id,
                    'export_proceed_realization_id' => $deduction_data->export_proceed_realization_id,
                    'account_head_id' => $deduction_data->account_head_id,
                    'account_head' => $deduction_data->accountHead->name,
                    'document_currency' => $deduction_data->document_currency,
                    'conversion_rate' => $deduction_data->conversion_rate,
                    'domestic_currency' => $deduction_data->domestic_currency,
                    'status' => $deduction_data->status,
                ];
            }
        }
        if ($realization->distributions) {
            foreach ($realization->distributions as $distribution_data) {
                $distributions[] = [
                    'id' => $distribution_data->id,
                    'export_proceed_realization_id' => $distribution_data->export_proceed_realization_id,
                    'document_submission_transaction_id' => $distribution_data->document_submission_transaction_id,
                    'account_head_id' => $distribution_data->account_head_id,
                    'account_head' => $distribution_data->accountHead->name,
                    'ac_loan_no' => $distribution_data->ac_loan_no,
                    'document_currency' => $distribution_data->document_currency,
                    'conversion_rate' => $distribution_data->conversion_rate,
                    'domestic_currency' => $distribution_data->domestic_currency,
                    'status' => $distribution_data->status,
                ];
            }
        }
        $realization_data = [
            'id' => $realization->id,
            'beneficiary_id' => $realization->beneficiary_id,
            'buyer_id' => $realization->buyer_id,
            'document_submission_id' => $realization->document_submission_id,
            'export_lc_id' => $realization->export_lc_id,
            'sales_contract_id' => $realization->sales_contract_id,
            'receive_date' => $realization->receive_date,
            'lc_sc_no' => $realization->lc_sc_no,
            'currency_id' => $realization->currency_id,
            'currency' => $realization->currency->currency_name,
            'bill_no' => $realization->documentSubmission->bank_ref_bill,
            'bill_invoice_date' => $realization->bill_invoice_date,
            'bill_invoice_amount' => $realization->bill_invoice_amount,
            'negotiated_amount' => $realization->negotiated_amount,
            'document_currency' => $realization->document_currency,
            'domestic_currency' => $realization->domestic_currency,
            'deductions' => $deductions,
            'distributions' => $distributions,
        ];

        return response()->json($realization_data);
    }

    public function show(ExportProceedsRealization $realization): JsonResponse
    {
        return response()->json($realization);
    }

    public function delete($id)
    {
        try {
            DB::beginTransaction();
            ExportProceedsRealization::query()->find($id)->delete();
            DB::commit();
            Session::flash('error', 'Data Deleted Successfully');

            return redirect()->back();
        } catch (\Exception $exception) {
            Session::flash('error', 'Something Wrong');
        }
    }

    public function deleteDetail($id)
    {
        try {
            DB::beginTransaction();
            $details = ExportProceedDeduction::query()->find($id) ?? ExportProceedDistribution::query()->find($id) ;
            $details->delete();
            DB::commit();

            return response()->json([
                'status' => 200,
                'error' => null,
                'message' => ApplicationConstant::S_DELETED,
            ]);
        } catch (\Exception $exception) {
            return response()->json([
                'status' => 500,
                'error' => $exception->getMessage(),
                'message' => ApplicationConstant::SOMETHING_WENT_WRONG,
            ]);
        }
    }
}
