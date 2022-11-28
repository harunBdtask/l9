<?php

namespace SkylarkSoft\GoRMG\Inventory\Requests;

use Illuminate\Foundation\Http\FormRequest;
use SkylarkSoft\GoRMG\Inventory\Rules\YarnReceiveQtyRule;

class YarnReceiveDetailsFormRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'yarn_receive_id'     => 'required',
            'yarn_count_id'       => 'required',
            'yarn_composition_id' => 'required',
            'yarn_type_id'        => 'required',
            'yarn_brand'          => 'required',
            'store_id'            => 'required',
            'uom_id'              => 'required',
            'rate'                => 'required',
            'yarn_color'                => 'required',
            'supplier_id'         => 'required',
            'receive_qty'         => ['required', new YarnReceiveQtyRule()],
            'yarn_lot'            => 'required',
        ];
    }

    public function messages(): array
    {
        return [
            'required' => 'Required',
            'unique'   => 'Must be unique',
        ];
    }
}
