<?php

namespace SkylarkSoft\GoRMG\BasicFinance\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CostCenterFormRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'cost_center' => 'required|unique:bf_cost_centers,cost_center,' . $this->segment(3) . ',id,deleted_at,NULL',
        ];
    }

    public function messages(): array
    {
        return [
            'cost_center.required' => 'Department name field is required',
        ];
    }
}
