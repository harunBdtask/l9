<?php

namespace SkylarkSoft\GoRMG\Procurement\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class RequisitionFormRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return Auth::check();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'factory_id' => 'required',
            'date' => 'required',
            'required_date' => 'required',
            'department_id' => 'required',
            'department_head' => 'required',
            'procurement_requisition_details.*.item_id' => 'required',
            'procurement_requisition_details.*.qty' => 'required|numeric',
        ];
    }
}
