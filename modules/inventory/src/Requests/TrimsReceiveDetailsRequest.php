<?php

namespace SkylarkSoft\GoRMG\Inventory\Requests;


use Illuminate\Foundation\Http\FormRequest;
use SkylarkSoft\GoRMG\Inventory\Rules\TrimsReceiveQty;

class TrimsReceiveDetailsRequest extends FormRequest
{

    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            '*.item_id' => 'required',
            '*.uom_id' => 'required',
            '*.style_name' => 'exists:trims_booking_details',
            '*.receive_qty' => ['numeric', new TrimsReceiveQty],
//            '*.amount' => ['required', 'numeric'],
        ];
    }

    public function messages(): array
    {
        return [
            'required' => 'Required'
        ];
    }
}
