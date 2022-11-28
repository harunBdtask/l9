<?php

namespace SkylarkSoft\GoRMG\Finance\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use SkylarkSoft\GoRMG\Finance\Rules\AcTypeMismatch;

class CustomerBillEntryRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation messages that apply to the erroneous request.
     *
     * @return array
     */
    public function messages(): array
    {
        return [
            'group_id.required' => 'Group is required.',
            'company_id.required' => 'Company is required.',
            'project_id.required' => 'Project is required.',
            'bill_basis.required' => 'Bill Basis is required.',
            'bill_date.required' => 'Billing Date is required.'
        ];
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        return [
            'group_id' => 'required',
            'company_id' => 'required',
            'project_id' => 'required',
            'bill_basis' => 'required',
            'bill_date' => 'required',
        ];
    }
}
