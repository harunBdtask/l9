<?php

namespace SkylarkSoft\GoRMG\Subcontract\Requests\SubTextileRequests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class SubDyeingTumbleFormRequest extends FormRequest
{
    public function authorize(): bool
    {
        return Auth::check();
    }

    public function rules(): array
    {
        return [
            'factory_id' => 'required',
            'supplier_id' => 'required',
            'entry_basis' => 'required',
            'sub_dyeing_batch_id' => 'required_if:entry_basis,1',
            'sub_dyeing_batch_no' => 'required_if:entry_basis,1',
            'sub_textile_order_id' => 'required_if:entry_basis,2',
            'sub_textile_order_no' => 'required_if:entry_basis,2',
            'production_date' => 'required',
            'shift_id' => 'required',
            'tumble_details.*.no_of_roll' => 'required',
            'tumble_details.*.finish_qty' => 'required',
            'tumble_details.*.unit_cost' => 'required',
        ];
    }

    public function messages(): array
    {
        return [
            'factory_id.required' => 'Factory field is required',
            'supplier_id.required' => 'Supplier field is required',
            'sub_dyeing_batch_id.required' => 'Batch field is required',
            'sub_textile_order_id.required' => 'Order field is required',
            'sub_dyeing_unit_id.required' => 'Dyeing Unit field is required',
            'machine_id.required' => 'Machine field is required',
            'shift_id.required' => 'Shift field is required',
        ];
    }
}
