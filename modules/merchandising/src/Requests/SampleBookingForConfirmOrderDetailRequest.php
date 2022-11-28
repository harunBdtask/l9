<?php

namespace SkylarkSoft\GoRMG\Merchandising\Requests;

use Illuminate\Foundation\Http\FormRequest;
use SkylarkSoft\GoRMG\Merchandising\Rules\SampleBookingConfirmOrderQtyRule;

class SampleBookingForConfirmOrderDetailRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }


    public function rules(): array
    {
        return [
            '*.requisition_id'        => 'required',
            '*.requisition_detail_id' => 'required',
            '*.sample_id'             => 'required|array|min:1',
            '*.gmts_item_id'          => 'required',
            '*.fabric_nature_id'      => 'required',
            '*.gmts_color_id'         => 'required',
            '*.color_type_id'         => 'required',
            '*.fabric_description_id' => 'required',
            '*.fabric_source_id'      => 'required',
            '*.dia'                   => 'required',
            '*.gsm'                   => 'required',
            '*.uom_id'                => 'required',
            '*.required_qty'          => ['required', 'numeric', new SampleBookingConfirmOrderQtyRule],
            '*.process_loss'          => 'nullable|numeric',
            '*.rate'                  => 'required|numeric',
            '*.total_qty'             => 'required|numeric',
            '*.amount'                => 'required|numeric',
            '*.remarks'               => 'nullable|string|max:255'
        ];
    }

    public function messages(): array
    {
        return [
            'required' => 'Required'
        ];
    }
}
