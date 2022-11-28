<?php

namespace SkylarkSoft\GoRMG\Knitting\Requests;

use Illuminate\Foundation\Http\FormRequest;
use SkylarkSoft\GoRMG\Knitting\Rules\KnittingRollDeliveryRule;

class KnittingRollDeliveryChallanDetailRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     * @return bool
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        return [
            'challan_no' => 'required',
            'plan_info_id' => 'required',
            'knitting_program_id' => 'required',
            'knitting_program_roll_id' => ['required', new KnittingRollDeliveryRule()],
        ];
    }

    public function messages(): array
    {
        return [
            'required' => 'Field is required',
        ];
    }
}
