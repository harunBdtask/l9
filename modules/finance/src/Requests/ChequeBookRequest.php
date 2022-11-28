<?php

namespace SkylarkSoft\GoRMG\Finance\Requests;

use Illuminate\Foundation\Http\FormRequest;
use SkylarkSoft\GoRMG\Finance\Rules\ChequeNoDuplicate;

class ChequeBookRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'bank_id' => 'required',
            'bank_account_id' => 'required',
            'cheque_book_no' => 'required',
            'cheque_no_from' => 'required',
            'cheque_no_to' => 'required',
            'total_page' => 'required',
            'details.*.cheque_no' => ['required', new ChequeNoDuplicate()],
        ];
    }

    public function messages(): array
    {
        return [
            'bank_id.required' => 'This Bank name field is required',
            'bank_account_id.required' => 'This Bank account name field is required',
        ];
    }
}
