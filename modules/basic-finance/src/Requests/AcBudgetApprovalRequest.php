<?php

namespace SkylarkSoft\GoRMG\BasicFinance\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AcBudgetApprovalRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            '*.bf_ac_budget_id' => 'required',
            '*.bf_ac_budget_detail_id' => 'required',
            '*.bf_account_id' => 'required',
            '*.code' => 'required',
        ];
    }
}
