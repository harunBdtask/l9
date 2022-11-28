<?php

namespace SkylarkSoft\GoRMG\TrimsStore\Requests\V3\TrimsStoreIssueReturn;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class TrimsStoreIssueReturnFormRequest extends FormRequest
{
    public function authorize(): bool
    {
        return Auth::check();
    }

    public function rules(): array
    {
        return [
            'factory_id' => 'required',
            'return_source_id' => 'required',
            'store_id' => 'required',
            'return_basis_id' => 'required',
            'return_type_id' => 'required',
            'return_challan_no' => 'required',
            'issue_return_date' => 'required',
        ];
    }
}
