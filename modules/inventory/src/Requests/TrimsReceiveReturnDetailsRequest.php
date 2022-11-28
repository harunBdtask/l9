<?php


namespace SkylarkSoft\GoRMG\Inventory\Requests;


use Illuminate\Foundation\Http\FormRequest;
use SkylarkSoft\GoRMG\Inventory\Rules\TrimsReceiveReturnQty;

class TrimsReceiveReturnDetailsRequest extends FormRequest
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
//            '*.style_name' => 'required',
            '*.return_qty' => ['numeric', new TrimsReceiveReturnQty]
        ];
    }

    public function messages(): array
    {
        return [
            'required' => 'Required'
        ];
    }

}
