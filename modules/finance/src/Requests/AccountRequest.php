<?php

namespace SkylarkSoft\GoRMG\Finance\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use SkylarkSoft\GoRMG\Finance\Rules\AcTypeMismatch;

class AccountRequest extends FormRequest
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
            'name.required' => 'Account name is required.',
            'name.unique' => 'Account name is unique.',
            'code.required' => 'Account code is required.',
            'code.unique' => 'Account code is unique.',
            'code.min' => 'Account code length should be 9 digit long.',
            'code.max' => 'Account code length should be 9 digit long.',
            'type_id.required' => 'Account type is required.'
        ];
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        $accountId = request()->route('id');

        return [
            'name' => [
                'required',
                Rule::unique('fi_accounts', 'name')->ignore($this->id),
            ],
            'code' => 'required|min:9|max:9|unique:accounts,code,' . $accountId,
            'type_id' => 'required',
            'parent_ac' => [new AcTypeMismatch]
        ];
    }
}
