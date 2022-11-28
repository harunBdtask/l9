<?php

namespace SkylarkSoft\GoRMG\Finance\Rules;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Contracts\Validation\Rule;
use SkylarkSoft\GoRMG\Finance\Models\ChequeBookDetail;

class ChequeNoDuplicate implements Rule
{
    private $value;

    public function passes($attribute, $value): bool
    {
        $this->value = $value;
        $bankId = request('bank_id');
        $bankAccountId = request('bank_account_id');
        $chequeBook = ChequeBookDetail::query()
            ->whereHas('chequeBook', function (Builder $query) use ($bankId, $bankAccountId) {
                $query->where('bank_id', $bankId)->where('bank_account_id', $bankAccountId);
            })->where('cheque_no', $value)->first();

        if (isset($chequeBook)) {
            return false;
        }

        return true;
    }

    public function message(): string
    {
        return "{$this->value} cheque no. already added this account";
    }
}
