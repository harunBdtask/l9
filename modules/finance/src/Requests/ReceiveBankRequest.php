<?php

namespace SkylarkSoft\GoRMG\Finance\Requests;

use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ReceiveBankRequest extends FormRequest
{
    /**
     * @return bool
     */
    public function authorize(): bool
    {
        return Auth::check();
    }

    /**
     * @return array[]
     */
    public function rules(): array
    {
        return [
            'name' => [
                'required',
                Rule::unique('fi_receive_banks', 'name')
                    ->ignore($this->route('receive_bank'))
                    ->whereNull('deleted_at'),
            ]
        ];
    }
}
