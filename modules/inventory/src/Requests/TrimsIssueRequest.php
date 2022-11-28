<?php


namespace SkylarkSoft\GoRMG\Inventory\Requests;


use Illuminate\Foundation\Http\FormRequest;
use SkylarkSoft\GoRMG\Inventory\Rules\TrimsIssueQty;

class TrimsIssueRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'issue_date' => 'required',
            'factory_id' => 'required',
            'issue_basis' => 'required',
            'issue_purpose' => 'required',
            'issue_challan_no'=>'required', 
            'floor_no'=>'required',
            'store_id'=>'required',
            'sewing_source'=>'required',
            'details.*.item_id' => 'required',
            'details.*.uom_id' => 'required',
            'details.*.style_name' => 'required',
            'details.*.issue_qty' => ['required', new TrimsIssueQty]
            
        ];
    }

    public function messages(): array
    {
        return [
            'required' => 'Required'
        ];
    }
}