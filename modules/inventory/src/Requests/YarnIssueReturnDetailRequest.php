<?php

namespace SkylarkSoft\GoRMG\Inventory\Requests;

use Illuminate\Foundation\Http\FormRequest;
use SkylarkSoft\GoRMG\Inventory\Rules\YarnIssueReturnQtyRule;

class YarnIssueReturnDetailRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'yarn_lot'            => 'required',
            'issue_qty'           => 'required',
            'rate'                => 'required',
            'uom_id'              => 'required',
            'yarn_count_id'       => 'required',
            'yarn_composition_id' => 'required',
            'yarn_type_id'        => 'required',
            'store_id'            => 'required',
            'return_qty'          => ['required', new YarnIssueReturnQtyRule()],
        ];
    }

    public function messages(): array
    {
        return [
            'required' => 'Required'
        ];
    }
}
