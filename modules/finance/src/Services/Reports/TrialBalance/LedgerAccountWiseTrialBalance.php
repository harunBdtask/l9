<?php

namespace SkylarkSoft\GoRMG\Finance\Services\Reports\TrialBalance;

use Illuminate\Support\Collection;
use SkylarkSoft\GoRMG\Finance\Models\Account;
use SkylarkSoft\GoRMG\Finance\Models\Journal;

class LedgerAccountWiseTrialBalance implements TrialBalanceReportInterface
{
    public $data;

    public function __construct($data)
    {
        $this->data = $data;
    }

    public function formatView()
    {
        return view('finance::reports.trial-balance.ledger_account_wise_trail_balance_report', [
            'data' => $this->reportData()
        ]);
    }

    public function reportData(): Collection
    {
        $accounts = Account::with('accountInfo')->get();
        return collect($this->accountTypes())->map(function ($collection, $key) use ($accounts) {
            return collect($accounts)->where('type_id', $key)
                ->where('account_type', Account::PARENT)
                ->map(function ($parentAccount) use ($key, $collection, $accounts) {
                    return collect($accounts)->where('account_type', Account::GROUP)
                        ->where('accountInfo.parent_account_id', $parentAccount->id)
                        ->map(function ($groupAccount) use ($key, $collection, $accounts, $parentAccount) {
                            return collect($accounts)->where('account_type', Account::CONTROL)
                                ->where('accountInfo.group_account_id', $groupAccount->id)
                                ->map(
                                    function ($controlAccount) use (
                                        $key, $collection, $accounts, $parentAccount, $groupAccount
                                    ) {
                                        return collect($accounts)->whereIn('account_type', [Account::LEDGER, Account::SUB_LEDGER])
                                            ->where('accountInfo.control_account_id', $controlAccount->id)
                                            ->map(function ($ledgerAccount) use (
                                                $key, $collection, $accounts, $parentAccount, $groupAccount, $controlAccount
                                            ) {
                                                $searchParams = [
                                                    'type_id' => $key,
                                                    'start_date' => $this->data['start_date'],
                                                    'end_date' => $this->data['end_date'],
                                                    'parent_account_id' => $parentAccount->id,
                                                    'group_account_id' => $groupAccount->id,
                                                    'control_account_id' => $controlAccount->id,
                                                    'ledger_account_id' => $ledgerAccount->id,
                                                ];

                                                $openingBalance = $this->openingBalanceCalculate($searchParams);
                                                $transactionBalance = $this->transactionBalanceCalculate($searchParams);
                                                $balanceDebit = $openingBalance['debit'] + $transactionBalance['debit'];
                                                $balanceCredit = $openingBalance['credit'] + $transactionBalance['credit'];

                                                return [
                                                    'account_type' => $collection,
                                                    'parent_account_name' => $parentAccount->name,
                                                    'group_account_name' => $groupAccount->name,
                                                    'control_account_name' => $controlAccount->name,
                                                    'account_name' => "{$ledgerAccount->name}  ( {$ledgerAccount->code} )",
                                                    'opening_balance_debit' => $openingBalance['debit'] ?? 0,
                                                    'opening_balance_credit' => $openingBalance['credit'] ?? 0,
                                                    'transaction_debit' => $transactionBalance['debit'] ?? 0,
                                                    'transaction_credit' => $transactionBalance['credit'] ?? 0,
                                                    'balance_debit' => $balanceDebit ?? 0,
                                                    'balance_credit' => $balanceCredit ?? 0,
                                                ];
                                            });
                                    }
                                );
                        });
                });
        });
    }

    public function openingBalanceCalculate(array $data): array
    {
        $journal = Journal::query()->where('trn_date', '<', $data['start_date'])
            ->whereHas('account', function ($query) use ($data) {
                $query->where('type_id', $data['type_id'])->whereHas('accountInfo', function ($query) use ($data) {
                    $query->has('groupAccount')->has('controlAccount')->where('account_id', $data['ledger_account_id']);
                });
            });

        $debit = $journal
            ->where('trn_type', 'dr')
            ->sum('trn_amount');

        $credit = $journal
            ->where('trn_type', 'cr')
            ->sum('trn_amount');

        return [
            'debit' => $debit,
            'credit' => $credit
        ];
    }

    public function transactionBalanceCalculate(array $data): array
    {
        $journal = Journal::query()->whereBetween('trn_date', [$data['start_date'], $data['end_date']])
            ->whereHas('account', function ($query) use ($data) {
                $query->where('type_id', $data['type_id'])->whereHas('accountInfo', function ($query) use ($data) {
                    $query->has('groupAccount')->has('controlAccount')->where('account_id', $data['ledger_account_id']);
                });
            });

        $debit = $journal
            ->where('trn_type', 'dr')
            ->sum('trn_amount');

        $credit = $journal
            ->where('trn_type', 'cr')
            ->sum('trn_amount');

        return [
            'debit' => $debit,
            'credit' => $credit
        ];
    }

    public function accountTypes(): array
    {
        return Account::$types;
    }
}
