<?php

namespace SkylarkSoft\GoRMG\Subcontract\Requests\SubTextileRequests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use SkylarkSoft\GoRMG\Subcontract\Rules\SubTextileRules\IssueQtyRule;
use SkylarkSoft\GoRMG\Subcontract\Rules\SubTextileRules\QtyRule;
use SkylarkSoft\GoRMG\Subcontract\Rules\SubTextileRules\StockCheckBeforeIssueRule;

class FabricIssueDetailsFormRequest extends FormRequest
{
    public function authorize(): bool
    {
        return Auth::check();
    }

    public function rules(): array
    {
        return [
            'total_roll' => 'required',
            'issue_qty' => ['required', new QtyRule(), new StockCheckBeforeIssueRule(), new IssueQtyRule()],
            'issue_return_qty' => 'sometimes|numeric|max:' . (int) request()->input('issue_qty'),
        ];
    }

    public function prepareForValidation()
    {
        $this->merge([
            'issue_return_qty' => empty(request()->input('issue_return_qty'))
                ? 0 : request()->input('issue_return_qty'),
        ]);
    }
}
