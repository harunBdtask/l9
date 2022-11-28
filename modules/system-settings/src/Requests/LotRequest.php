<?php

namespace SkylarkSoft\GoRMG\SystemSettings\Requests;

use Illuminate\Foundation\Http\FormRequest;
use SkylarkSoft\GoRMG\SystemSettings\Rules\UniqueLotNoRule;
use SkylarkSoft\GoRMG\SystemSettings\Rules\ValidColorForOrder;

class LotRequest extends FormRequest
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
            'lot_no.required' => 'Lot no is required.',
            'order_id.required' => 'PO is required.',
            'color_id.required' => 'Color is required.',
        ];
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'lot_no' => ['required',"not_regex:/([^\w\d\s&'.\-\)\(\/])+/i", new UniqueLotNoRule],
            'order_id' => 'required',
            'color_id' => ['required', new ValidColorForOrder],
        ];
    }
}
