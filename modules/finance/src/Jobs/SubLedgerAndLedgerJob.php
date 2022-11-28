<?php

namespace SkylarkSoft\GoRMG\Finance\Jobs;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Contracts\Queue\ShouldQueue;
use SkylarkSoft\GoRMG\Finance\Models\Account;
use SkylarkSoft\GoRMG\Finance\Models\AccountInfo;
use SkylarkSoft\GoRMG\Finance\Models\BankAccount;
use SkylarkSoft\GoRMG\Finance\Services\AccountCode\AccountCodeStrategy;

class SubLedgerAndLedgerJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * @var Request
     */
    private $request;

    private $bankAccount;

    /**
     * @var string
     */
    private $type;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(Request $request, $bankAccount, string $type)
    {
        $this->request = $request;
        $this->bankAccount = $bankAccount;
        $this->type = $type;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $previousBankAccount = null;
        if ($this->type == "update") {
            $previousBankAccount = BankAccount::query()->find($this->request['id']);
        }
        $controlAccount = Account::query()->with('accountInfo')->find($this->request['control_account_id']);
        $accountCode = (new AccountCodeStrategy())->setStrategy(Account::LEDGER)
            ->setType($controlAccount->type_id)
            ->setParentId($controlAccount->accountInfo->parent_account_id)
            ->setGroupId($controlAccount->accountInfo->group_account_id)
            ->setControlId($controlAccount->id)
            ->generate();

        $ledgerAccount = null;
        if ($this->type == "update") {
            $ledgerAccount = Account::query()->where('name', $previousBankAccount->bank->short_name)
                ->whereRelation('accountInfo', 'control_account_id', $previousBankAccount->control_account_id)
                ->first();
        } else {
            $ledgerAccount = new Account();
            $ledgerAccount->code = $accountCode;
            $ledgerAccount->bank_account_id = $this->bankAccount->id;
        }
        $ledgerAccount->name = $this->request['bank_short_name'];
        $ledgerAccount->particulars = null;
        $ledgerAccount->type_id = $controlAccount->type_id;
        $ledgerAccount->account_type = Account::LEDGER;
        $ledgerAccount->save();

        if ($this->type == "store") {
            $accountInfo = new AccountInfo();
            $accountInfo->accounts_id = $ledgerAccount->id;
            $accountInfo->parent_account_id = $controlAccount->accountInfo->parent_account_id;
            $accountInfo->group_account_id = $controlAccount->accountInfo->group_account_id;
            $accountInfo->control_account_id = $controlAccount->id;
            $accountInfo->save();
        }

        $accountCode = (new AccountCodeStrategy())->setStrategy(Account::SUB_LEDGER)
            ->setType($controlAccount->type_id)
            ->setParentId($controlAccount->accountInfo->parent_account_id)
            ->setGroupId($controlAccount->accountInfo->group_account_id)
            ->setControlId($controlAccount->id)
            ->setLedgerId($ledgerAccount->id)
            ->generate();

        $subLedgerAccount = null;
        if ($this->type == "update") {
            // $subLedgerAccount = Account::query()->where('name', $previousBankAccount->account_number)
            $subLedgerAccount = Account::query()->where('name', $this->request['ledger_name'])
                    ->whereRelation('accountInfo', 'control_account_id', $previousBankAccount->control_account_id)
                    ->whereRelation('accountInfo', 'ledger_account_id', $ledgerAccount->id)
                    ->first() ?? new Account();
        } else {
            $subLedgerAccount = new Account();
            $subLedgerAccount->code = $accountCode;
            $subLedgerAccount->bank_account_id = $this->bankAccount->id;
        }
        $subLedgerAccount->name = $this->request['ledger_name'];
        $subLedgerAccount->particulars = null;
        $subLedgerAccount->type_id = $controlAccount->type_id;
        $subLedgerAccount->account_type = Account::SUB_LEDGER;
        $subLedgerAccount->save();

        if ($this->type == "store") {
            $accountInfo = new AccountInfo();
            $accountInfo->accounts_id = $subLedgerAccount->id;
            $accountInfo->parent_account_id = $controlAccount->accountInfo->parent_account_id;
            $accountInfo->group_account_id = $controlAccount->accountInfo->group_account_id;
            $accountInfo->control_account_id = $controlAccount->id;
            $accountInfo->ledger_account_id = $ledgerAccount->id;
            $accountInfo->save();
        }
    }
}
