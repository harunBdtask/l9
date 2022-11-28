<?php

namespace SkylarkSoft\GoRMG\Inventory\Requests;

use Illuminate\Foundation\Http\FormRequest;
use SkylarkSoft\GoRMG\Inventory\Rules\YarnIssueQtyRule;

class YarnIssueFormRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check();
    }

    public function rules(): array
    {
        return [
            'uom_id' => 'required',
            'yarn_composition_id' => 'required',
            'store_id' => 'required',
            'yarn_type_id' => 'required',
            'yarn_count_id' => 'required',
            'issue_qty' => ['required', new YarnIssueQtyRule()],
        ];
    }
}
