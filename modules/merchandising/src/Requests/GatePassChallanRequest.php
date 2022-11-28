<?php

namespace SkylarkSoft\GoRMG\Merchandising\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class GatePassChallanRequest extends FormRequest
{
    public function authorize(): bool
    {
        return Auth::check();
    }

    public function messages(): array
    {
        return [
            //'challan_no.unique' => 'challan no should be unique',
           // 'challan_no.required' => 'challan no field is required',
            'challan_date.required' => 'challan date field is required',
            'department_id.required' => 'department field is required',
            'supplier_id.required' => 'party field is required',
            'good_id.required' => 'goods field is required',
            'status.required' => 'status field is required',

        ];
    }

    public function rules(): array
    {
        return [
            //'challan_no' => ['required', Rule::unique('mer_gate_pass_challans')->ignore(request()->segment(2))],
            'challan_date' => 'required',
            'department_id' => 'required',
            'supplier_id' => 'required',
            'good_id' => 'required',
            'status' => 'required',

        ];
    }

}
