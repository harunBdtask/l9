<?php

namespace SkylarkSoft\GoRMG\Inventory\Requests;

use Illuminate\Foundation\Http\FormRequest;

class YarnReceiveReturnFormRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check();
    }

    public function rules(): array
    {
        return [
            'factory_id' => 'required',
            'return_date' => 'required',
            'return_to' => 'required',
        ];
    }
}
