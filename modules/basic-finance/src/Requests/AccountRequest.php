<?php

namespace SkylarkSoft\GoRMG\BasicFinance\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use SkylarkSoft\GoRMG\BasicFinance\Rules\AcTypeMismatch;
use SkylarkSoft\GoRMG\BasicFinance\Rules\UniqueAccountCodeRule;
use SkylarkSoft\GoRMG\BasicFinance\Rules\UniqueAccountNameRule;

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
     * @return string[]
     */
    public function messages(): array
    {
        return [
            'name.required' => 'Account name is required.',
            'name.unique' => 'Account name is not unique.',
            'code.required' => 'Account code is required.',
            'code.unique' => 'Account code is not unique.',
//            'code.min' => 'Account code length should be 9 digit long.',
//            'code.max' => 'Account code length should be 9 digit long.',
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
        return [
            'name' => ['required', new UniqueAccountNameRule],
            'code' => ['required', 'max:16', new UniqueAccountCodeRule],
            'type_id' => 'required',
            'parent_ac' => [new AcTypeMismatch]
        ];
    }
}
