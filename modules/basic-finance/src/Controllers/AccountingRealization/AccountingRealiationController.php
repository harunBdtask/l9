<?php

namespace SkylarkSoft\GoRMG\BasicFinance\Controllers\AccountingRealization;

use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Session;
use Symfony\Component\HttpFoundation\Response;
use SkylarkSoft\GoRMG\BasicFinance\Models\AccountRealization;
use SkylarkSoft\GoRMG\BasicFinance\Services\FetchLeafNodesService;
use SkylarkSoft\GoRMG\BasicFinance\Services\FetchLeafNodesCodeService;
use SkylarkSoft\GoRMG\BasicFinance\Requests\AccountingRealizationRequest;

class AccountingRealiationController extends Controller
{
    public function index(Request $request)
    {
        $q = $request->q ?? null;
        $listData = AccountRealization::query()
            ->when($q, function ($query) use ($q) {
                $query->where('realization_number', $q);
            })
            ->orderBy('id', 'desc')->paginate();

        return view("basic-finance::accounting-realization.list", [
            'lists' => $listData
        ]);
    }

    public function create()
    {
        return view("basic-finance::accounting-realization.create-form");
    }

    public function edit()
    {
        return view("basic-finance::accounting-realization.edit-form");
    }

    public function fetch(AccountRealization $accountRealization)
    {
        try {
            $foreignBankAcData = $this->fetchForeignBankChargeAccounts();
            $deductionAcData = $this->fetchDeductionChargeAccounts();

            $foreign_bank_charge = $this->formatBankChargeAccounts($foreignBankAcData, $accountRealization, 'foreign_bank_charge');
            $deduction = $this->formatBankChargeAccounts($deductionAcData, $accountRealization, 'deduction');

            $data = [
                'id' => $accountRealization->id,
                'realization_type_source' => $accountRealization->realization_type_source,
                'factory_id' => $accountRealization->factory_id,
                'bf_project_id' => $accountRealization->bf_project_id,
                'bf_unit_id' => $accountRealization->bf_unit_id,
                'realization_type' => $accountRealization->realization_type,
                'document_submission_id' => $accountRealization->document_submission_id,
                'commercial_realization_id' => $accountRealization->commercial_realization_id,
                'realization_number' => $accountRealization->realization_number,
                'export_lc_id' => $accountRealization->export_lc_id,
                'sales_contract_id' => $accountRealization->sales_contract_id,
                'export_invoice_id' => $accountRealization->export_invoice_id,
                'sc_number' => $accountRealization->sc_number,
                'lc_number' => $accountRealization->lc_number,
                'invoice_number' => $accountRealization->invoice_number,
                'realization_date' => $accountRealization->realization_date,
                'realization_rate' => $accountRealization->realization_rate,
                'currency_id' => $accountRealization->currency_id,
                'total_value' => $accountRealization->total_value,
                'realized_value' => $accountRealization->realized_value,
                'short_realization' => $accountRealization->short_realization,
                'foreign_bank_charge' => $foreign_bank_charge,
                'deduction' => $deduction,
                'total_deduction' => $accountRealization->total_deduction,
                'distribution' => $accountRealization->distribution,
                'loan_distribution' => $accountRealization->loan_distribution,
                'total_distribution' => $accountRealization->total_distribution,
                'grand_total' => $accountRealization->grand_total,
                'realized_gain_loss' => $accountRealization->realized_gain_loss,
                'realized_difference' => $accountRealization->realized_difference,
                'buyers' => $accountRealization->buyers,
                'styles' => $accountRealization->styles,
                'po_numbers' => $accountRealization->po_numbers,
            ];

            $status = Response::HTTP_OK;
            $message = \SUCCESS_MSG;
        } catch (\Exception $e) {
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

    private function fetchForeignBankChargeAccounts(): array
    {
        $foreignBankAcData = (new FetchLeafNodesCodeService('5303001000000'))->handle();
        $formattedData = [];
        if ($foreignBankAcData['data'] && count($foreignBankAcData['data'])) {
            collect($foreignBankAcData['data'])->where('code', '!=', '5303001009000')->map(function ($account) use (&$formattedData) {
                $formattedData[] = [
                    'bf_account_id' => $account['id'],
                    'bf_account' => $account['bf_account'],
                    'amount_usd' => '',
                    'con_rate' => '',
                    'amount_bdt' => '',
                    'percentage' => '',
                ];
            });
            $added_data = [];
            collect($foreignBankAcData['data'])->where('code', '5303001009000')->map(function ($account) use (&$added_data) {
                $added_data = [
                    'bf_account_id' => $account['id'],
                    'bf_account' => $account['bf_account'],
                    'amount_usd' => '',
                    'con_rate' => '',
                    'amount_bdt' => '',
                    'percentage' => '',
                ];
            });
            \array_push($formattedData, $added_data);
        }

        return $formattedData;
    }

    private function fetchDeductionChargeAccounts(): array
    {
        $deductionAcData = (new FetchLeafNodesCodeService('5303000000000', '5303001000000'))->handle();
        $formattedData = [];
        if ($deductionAcData['data'] && count($deductionAcData['data'])) {
            collect($deductionAcData['data'])->map(function ($account) use (&$formattedData) {
                $formattedData[] = [
                    'bf_account_id' => $account['id'],
                    'bf_account' => $account['bf_account'],
                    'amount_usd' => '',
                    'con_rate' => '',
                    'amount_bdt' => '',
                    'percentage' => '',
                ];
            });
        }

        return $formattedData;
    }

    private function formatBankChargeAccounts($accountsData, $item, $db_column_name): array
    {
        $bank_charge = [];
        if ($accountsData && is_array($accountsData) && count($accountsData)) {
            foreach ($accountsData as $account) {
                $bf_account_id = $account['bf_account_id'];
                $bf_account = $account['bf_account'];
                $amount_usd = '';
                $con_rate = '';
                $amount_bdt = '';
                $percentage = '';
                if ($item->$db_column_name && \is_array($item->$db_column_name) && count($item->$db_column_name) && collect($item->$db_column_name)->where('bf_account_id', $bf_account_id)->count()) {
                    $fcItemData = collect($item->$db_column_name)->where('bf_account_id', $bf_account_id)->first();
                    $amount_usd = $fcItemData['amount_usd'];
                    $con_rate = $fcItemData['con_rate'];
                    $amount_bdt = $fcItemData['amount_bdt'];
                    $percentage = $fcItemData['percentage'];
                }
                $bank_charge[] = [
                    'bf_account_id' => $bf_account_id,
                    'bf_account' => $bf_account,
                    'amount_usd' => $amount_usd,
                    'con_rate' => $con_rate,
                    'amount_bdt' => $amount_bdt,
                    'percentage' => $percentage,
                ];
            }
        }
        return $bank_charge;
    }

    public function store(AccountingRealizationRequest $request)
    {
        try {
            DB::beginTransaction();

            $data = new AccountRealization();
            $data->fill($request->all());
            $data->save();

            DB::commit();
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

    public function update(AccountingRealizationRequest $request, AccountRealization $accountRealization)
    {
        try {
            DB::beginTransaction();

            if ($accountRealization->approve_status == 0) {
                $accountRealization->fill($request->all());
                $accountRealization->save();
            }

            DB::commit();
            $data = $accountRealization;
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

    public function destroy(AccountRealization $accountRealization)
    {
        try {
            DB::beginTransaction();
            if ($accountRealization->approve_status == 0) {
                $accountRealization->delete();
            }
            DB::commit();
            Session::flash('success', S_DEL_MSG);
        } catch (\Exception $e) {
            Session::flash('error', \SOMETHING_WENT_WRONG);
            DB::rollBack();
        }

        return \redirect()->back();
    }
}
