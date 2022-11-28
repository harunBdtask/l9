<?php

namespace SkylarkSoft\GoRMG\Subcontract\Requests\SubTextileRequests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class FabricIssueFormRequest extends FormRequest
{
    public function authorize(): bool
    {
        return Auth::check();
    }

    public function rules(): array
    {
        return [
            'factory_id' => 'required',
            'supplier_id' => 'required',
            'sub_grey_store_id' => 'required',
            'sub_textile_order_id' => 'required',
            'sub_dyeing_unit_id' => 'required',
//            'challan_no' => [
//                'required',
//                Rule::unique('sub_grey_store_issues', 'challan_no')
//                    ->ignore($this->route('greyStoreIssue'))
//                    ->whereNull('deleted_at'),
//            ],
        ];
    }

    public function messages(): array
    {
        return [
            'supplier_id.required' => 'The party field is required',
        ];
    }
}
