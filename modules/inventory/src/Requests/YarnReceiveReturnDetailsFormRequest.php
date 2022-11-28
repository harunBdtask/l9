<?php

namespace SkylarkSoft\GoRMG\Inventory\Requests;

use Illuminate\Foundation\Http\FormRequest;
use SkylarkSoft\GoRMG\Inventory\Rules\YarnReceiveReturnQtyRule;

class YarnReceiveReturnDetailsFormRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check();
    }

    public function rules(): array
    {
        return [
            'yarn_count_id' => 'required',
            'yarn_composition_id' => 'required',
            'yarn_type_id' => 'required',
            'yarn_lot' => 'required',
            'return_qty' => ['required', new YarnReceiveReturnQtyRule]
        ];
    }
}
