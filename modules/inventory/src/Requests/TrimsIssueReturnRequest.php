<?php


namespace SkylarkSoft\GoRMG\Inventory\Requests;


use Illuminate\Foundation\Http\FormRequest;
use SkylarkSoft\GoRMG\Inventory\Rules\TrimsIssueReturnQty;
use SkylarkSoft\GoRMG\Inventory\Rules\TrimsReceiveReturnQty;

class TrimsIssueReturnRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'factory_id' => 'required',
            'return_date' => 'required|date',
            'store_id' => 'required',
            'challan_no' => 'required',
            'details.*.item_id' => 'required',
            'details.*.uom_id' => 'required',
            'details.*.style_name' => 'required',
            'details.*.return_qty' => ['required', 'numeric', 'gt:0', new TrimsIssueReturnQty]
        ];
    }

    public function messages(): array
    {
        return [
            'factory_id.required' => 'This factory name field is required',
            'store_id.required' => 'This store name field is required',
            'details.*.item_id.required' => 'This item name field is required',
            'details.*.uom_id.required' => 'This uom name field is required',
            'details.*.return_qty.required' => 'This return qty field is required',
        ];
    }
}
