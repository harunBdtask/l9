<?php

namespace SkylarkSoft\GoRMG\TrimsStore\Requests\V3\TrimsStoreIssue;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class TrimsStoreIssueFormRequest extends FormRequest
{
    public function authorize(): bool
    {
        return Auth::check();
    }

    public function rules(): array
    {
        return [
            'factory_id' => 'required',
            'source_id' => 'required',
            'store_id' => 'required',
            'issue_basis_id' => 'required',
            'issue_type_id' => 'required',
            'challan_no' => 'required',
            'issue_date' => 'required',
        ];
    }
}
