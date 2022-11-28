<?php

namespace SkylarkSoft\GoRMG\BasicFinance\Controllers\AccountingRealization;

use Carbon\Carbon;
use App\Http\Controllers\Controller;
use Exception;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\JsonResponse;
use SkylarkSoft\GoRMG\BasicFinance\Models\Account;
use SkylarkSoft\GoRMG\BasicFinance\Models\AccountRealization;
use SkylarkSoft\GoRMG\BasicFinance\Models\CostCenter;
use SkylarkSoft\GoRMG\BasicFinance\Models\Department;
use SkylarkSoft\GoRMG\BasicFinance\Models\Voucher;
use SkylarkSoft\GoRMG\BasicFinance\Services\CurrencyService;
use Symfony\Component\HttpFoundation\Response;

class RealizationToVoucherController extends Controller
{

    public function create()
    {
        return view('basic-finance::accounting-realization.vouchers.journal_voucher');
    }

    /**
     * @param AccountRealization $accountRealization
     * @return JsonResponse
     */
    public function getRealizationData(AccountRealization $accountRealization): JsonResponse
    {
        try {
            $realizationType = AccountRealization::REALIZATION_TYPES[$accountRealization->realization_type];
            $realizationNumber = $accountRealization->realization_number;
            $currencyName = $accountRealization->currency->currency_name;
            $voucher['factory_id'] = $accountRealization->factory_id;
            $voucher['factory_name'] = $accountRealization->factory->factory_name;
            $voucher['project_id'] = $accountRealization->bf_project_id;
            $voucher['project_name'] = $accountRealization->bfProject->project;
            $voucher['unit_id'] = $accountRealization->bf_unit_id;
            $voucher['unit_name'] = $accountRealization->bfUnit->unit;
            $voucher['server_date'] = Carbon::now()->toDateTimeLocalString();
            $voucher['voucher_no'] = Voucher::generateVoucherNo('journal');
            $voucher['trn_date'] = Carbon::now()->format('Y-m-d');
            $voucher['reference_no'] = $realizationType . '-' . $realizationNumber;
            $voucher['type_id'] = 3;
            $voucher['account_realization_id'] = $accountRealization->id;
            $voucher['currency_id'] = collect(CurrencyService::currencies())
                                          ->where('name', $currencyName)
                                          ->first()['id'] ?? null;
            $voucher['details']['file_no'] = '';
            $voucher['details']['items'] = [];
            $voucher['details']['type_id'] = 3;
            $voucher['details']['unit_id'] = $voucher['unit_id'];
            $voucher['details']['trn_date'] = $voucher['trn_date'];
            $voucher['details']['factory_id'] = $voucher['factory_id'];
            $voucher['details']['project_id'] = $voucher['project_id'];
            $voucher['details']['voucher_no'] = $voucher['voucher_no'];
            $voucher['details']['currency_id'] = $voucher['currency_id'];
            $voucher['details']['server_date'] = $voucher['server_date'];
            $voucher['details']['reference_no'] = $voucher['reference_no'];
            $voucher['details']['items'] = array_merge(
                $voucher['details']['items'],
                $this->totalValueItem($accountRealization, $voucher['reference_no'])
            );
            $voucher['details']['items'] = array_merge(
                $voucher['details']['items'],
                $this->gainLossItem($accountRealization, $voucher['reference_no'])
            );
            $voucher['details']['items'] = array_merge(
                $voucher['details']['items'],
                $this->foreignBankItems($accountRealization, $voucher['reference_no'])
            );
            $voucher['details']['items'] = array_merge(
                $voucher['details']['items'],
                $this->deductionItems($accountRealization, $voucher['reference_no'])
            );
            $voucher['details']['items'] = array_merge(
                $voucher['details']['items'],
                $this->distributionItems($accountRealization, $voucher['reference_no'])
            );
            $voucher['details']['items'] = array_merge(
                $voucher['details']['items'],
                $this->loadDistributionItems($accountRealization, $voucher['reference_no'])
            );
            $voucher['details']['total_debit'] = numberFormat(collect($voucher['details']['items'])->sum('debit'));
            $voucher['details']['total_credit'] = numberFormat(collect($voucher['details']['items'])->sum('credit'));
            $voucher['details']['total_debit_fc'] = numberFormat(collect($voucher['details']['items'])->sum('dr_fc'));
            $voucher['details']['total_credit_fc'] = numberFormat(collect($voucher['details']['items'])->sum('cr_fc'));
            $voucher['amount'] = numberFormat(collect($voucher['details']['items'])->sum('debit'));

            return response()->json([
                'message' => 'Fetch realization list successfully',
                'data' => $voucher,
                'status' => Response::HTTP_OK,
            ], Response::HTTP_OK);
        } catch (Exception $e) {
            return response()->json([
                'message' => $e->getMessage(),
                'line' => $e->getLine(),
                'status' => Response::HTTP_INTERNAL_SERVER_ERROR,
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * @return Builder|Model|object|null
     */
    private function getDepartment()
    {
        return Department::query()
                ->where('department', 'Accounts')
                ->first() ?? null;
    }

    /**
     * @return Builder|Model|object|null
     */
    private function getCostCenter()
    {
        return CostCenter::query()
                ->where('cost_center', 'Head Office')
                ->first() ?? null;
    }

    /**
     * @param $accountRealization
     * @param $referenceNo
     * @return array[]
     */
    private function totalValueItem($accountRealization, $referenceNo = null): array
    {
        $totalValue = $accountRealization->total_value;
        $totalValueAccount = null;

        if ($accountRealization->realization_type == 1) {
            $totalValueAccount = Account::query()->where('name', 'Local Sales')->first();
        } else {
            $totalValueAccount = Account::query()->where('name', 'Export Sales')->first();
        }

        return [
            [
                "account_id" => $totalValueAccount->id ?? null,
                "account_code" => $totalValueAccount->code ?? null,
                "account_name" => $totalValueAccount->name ?? null,
                "department_id" => $this->getDepartment()['id'] ?? null,
                "department_name" => $this->getDepartment()['department'] ?? null,
                "const_center" => $this->getCostCenter()['id'] ?? null,
                "const_center_name" => $this->getCostCenter()['cost_center'] ?? null,
                "conversion_rate" => $totalValue['con_rate'] ?? 0,
                "dr_fc" => 0,
                "cr_fc" => $totalValue['amount_usd'] ?? 0,
                "dr_bd" => 0,
                "cr_bd" => $totalValue['amount_bdt'] ?? 0,
                "debit" => 0,
                "credit" => $totalValue['amount_bdt'] ?? 0,
                "narration" => "Being the amount realized against {$referenceNo}",
            ]
        ];
    }

    /**
     * @param $accountRealization
     * @param $referenceNo
     * @return array|array[]
     */
    private function gainLossItem($accountRealization, $referenceNo = null): array
    {
        $gainLoss = $accountRealization->realized_gain_loss;

        if ($gainLoss != 0) {
            $debit = min($gainLoss, 0);
            $credit = max($gainLoss, 0);
            $gainLossAccount = Account::query()->where('name', 'Export Realization Gain/Loss')
                ->first();

            return [
                [
                    "account_id" => $gainLossAccount->id ?? null,
                    "account_code" => $gainLossAccount->code ?? null,
                    "account_name" => $gainLossAccount->name ?? null,
                    "department_id" => $this->getDepartment()['id'] ?? null,
                    "department_name" => $this->getDepartment()['department'] ?? null,
                    "const_center" => $this->getCostCenter()['id'] ?? null,
                    "const_center_name" => $this->getCostCenter()['cost_center'] ?? null,
                    "conversion_rate" => 0,
                    "dr_fc" => 0,
                    "cr_fc" => 0,
                    "dr_bd" => abs($debit) ?? 0,
                    "cr_bd" => $credit ?? 0,
                    "debit" => abs($debit) ?? 0,
                    "credit" => $credit ?? 0,
                    "narration" => "Being the amount Gain/Loss against {$referenceNo}",
                ]
            ];
        }

        return [];
    }

    /**
     * @param $accountRealization
     * @param $referenceNo
     * @return array
     */
    private function foreignBankItems($accountRealization, $referenceNo = null): array
    {
        $foreignBanks = $accountRealization->foreign_bank_charge;

        $items = [];
        foreach ($foreignBanks as $foreignBank) {
            $account = $foreignBank['bf_account'];

            if ($foreignBank['amount_bdt'] != 0) {
                $items[] = [
                    "account_id" => $account['id'] ?? null,
                    "account_code" => $account['code'] ?? null,
                    "account_name" => $account['name'] ?? null,
                    "department_id" => $this->getDepartment()['id'] ?? null,
                    "department_name" => $this->getDepartment()['department'] ?? null,
                    "const_center" => $this->getCostCenter()['id'] ?? null,
                    "const_center_name" => $this->getCostCenter()['cost_center'] ?? null,
                    "conversion_rate" => $foreignBank['con_rate'] ?? 0,
                    "dr_fc" => $foreignBank['amount_usd'] ?? 0,
                    "cr_fc" => 0,
                    "dr_bd" => $foreignBank['amount_bdt'] ?? 0,
                    "cr_bd" => 0,
                    "debit" => $foreignBank['amount_bdt'] ?? 0,
                    "credit" => 0,
                    "narration" => "Being the amount Deducted against {$referenceNo}",
                ];
            }
        }

        return $items;
    }

    /**
     * @param $accountRealization
     * @param $referenceNo
     * @return array
     */
    private function deductionItems($accountRealization, $referenceNo = null): array
    {
        $deductions = $accountRealization->deduction;

        $items = [];
        foreach ($deductions as $deduction) {
            $account = $deduction['bf_account'];

            if ($deduction['amount_bdt'] != 0) {
                $items[] = [
                    "account_id" => $account['id'] ?? null,
                    "account_code" => $account['code'] ?? null,
                    "account_name" => $account['name'] ?? null,
                    "department_id" => $this->getDepartment()['id'] ?? null,
                    "department_name" => $this->getDepartment()['department'] ?? null,
                    "const_center" => $this->getCostCenter()['id'] ?? null,
                    "const_center_name" => $this->getCostCenter()['cost_center'] ?? null,
                    "conversion_rate" => $deduction['con_rate'] ?? 0,
                    "dr_fc" => $deduction['amount_usd'] ?? 0,
                    "cr_fc" => 0,
                    "dr_bd" => $deduction['amount_bdt'] ?? 0,
                    "cr_bd" => 0,
                    "debit" => $deduction['amount_bdt'] ?? 0,
                    "credit" => 0,
                    "narration" => "Being the amount Deducted against {$referenceNo}",
                ];
            }
        }

        return $items;
    }

    /**
     * @param $accountRealization
     * @param $referenceNo
     * @return array
     */
    private function distributionItems($accountRealization, $referenceNo = null): array
    {
        $distributions = $accountRealization->distribution;

        $items = [];
        foreach ($distributions as $distribution) {
            $account = $distribution['bf_account'];

            $items[] = [
                "account_id" => $account['id'] ?? null,
                "account_code" => $account['code'] ?? null,
                "account_name" => $account['name'] ?? null,
                "department_id" => $this->getDepartment()['id'] ?? null,
                "department_name" => $this->getDepartment()['department'] ?? null,
                "const_center" => $this->getCostCenter()['id'] ?? null,
                "const_center_name" => $this->getCostCenter()['cost_center'] ?? null,
                "conversion_rate" => $distribution['con_rate'] ?? 0,
                "dr_fc" => $distribution['amount_usd'] ?? 0,
                "cr_fc" => 0,
                "dr_bd" => $distribution['amount_bdt'] ?? 0,
                "cr_bd" => 0,
                "debit" => $distribution['amount_bdt'] ?? 0,
                "credit" => 0,
                "narration" => "Being the amount Debited against {$referenceNo}",
            ];
        }

        return $items;
    }

    /**
     * @param $accountRealization
     * @param $referenceNo
     * @return array
     */
    private function loadDistributionItems($accountRealization, $referenceNo = null): array
    {
        $loanDistributions = $accountRealization->loan_distribution;

        $items = [];
        foreach ($loanDistributions as $loanDistribution) {
            $account = $loanDistribution['bf_account'];

            $items[] = [
                "account_id" => $account['id'] ?? null,
                "account_code" => $account['code'] ?? null,
                "account_name" => $account['name'] ?? null,
                "department_id" => $this->getDepartment()['id'] ?? null,
                "department_name" => $this->getDepartment()['department'] ?? null,
                "const_center" => $this->getCostCenter()['id'] ?? null,
                "const_center_name" => $this->getCostCenter()['cost_center'] ?? null,
                "conversion_rate" => $loanDistribution['con_rate'] ?? 0,
                "dr_fc" => $loanDistribution['amount_usd'] ?? 0,
                "cr_fc" => 0,
                "dr_bd" => $loanDistribution['amount_bdt'] ?? 0,
                "cr_bd" => 0,
                "debit" => $loanDistribution['amount_bdt'] ?? 0,
                "credit" => 0,
                "narration" => "Being the amount Debited against {$referenceNo}",
            ];
        }

        return $items;
    }

}
