<?php

namespace SkylarkSoft\GoRMG\Merchandising\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PqCommissionCostRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return auth()->check();
    }

    public function messages()
    {
        return [
      'quotation_id.required' => 'This field is required',
      'particular.required' => 'This field is required',
      'particular.integer' => 'This field is required',
      'commission_base.required' => 'This field is required',
      'commission_base.integer' => 'This field is required',
      'commission_rate.required' => 'This field is required',
      'commission_rate.numeric' => 'This field is required',
      'amount.required' => 'This field is required',
      'amount.numeric' => 'This field is required',
      'status.required' => 'This field is required',
      'status.integer' => 'This field is required',
    ];
    }

    public function rules()
    {
        $rules = [
      'quotation_id' => 'required',
      'particular' => 'required|integer',
      'commission_base' => 'required|integer',
      'commission_rate' => 'required|numeric',
      'amount' => 'required|numeric',
      'status' => 'required|integer',
    ];

        return $rules;
    }
}
