<?php

namespace SkylarkSoft\GoRMG\BasicFinance\Requests\Procurements;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class ProcurementRequisitionFormRequest extends FormRequest
{

    public function authorize(): bool
    {
        return Auth::check();
    }

    public function rules(): array
    {
        return [
            'factory_id' => 'required',
            'date' => 'required',
            'project_id' => 'required',
            'department_id' => 'required',
            'unit_id' => 'required',
            'procurement_requisition_details.*.item_type' => 'required',
            'procurement_requisition_details.*.item_category_id' => 'required',
            'procurement_requisition_details.*.item_id' => 'required',
        ];
    }
}
