<?php

namespace SkylarkSoft\GoRMG\GeneralStore\Requests;

use Illuminate\Foundation\Http\FormRequest;

class GsStockInVoucherRequest extends FormRequest
{

    public function authorize()
    {
        return true;
    }

    public function messages()
    {
        return [
            'trn_date.required' => 'Receive date is required',
            'trn_with.required' => 'Supplier is required'
        ];
    }

    public function rules()
    {
        $rules = [
            'trn_with' => 'required',
            'store' => 'required',
            'trn_date' => 'required|date',
            'item_id.*' => 'required',
            'qty.*' => 'required|numeric|min:0',
            'rate.*' => 'required|numeric|min:0',
        ];

        if ($this->store == 'yarn') {
            return array_merge($rules, ['brand.*' => 'required']);
        }

        if ($this->store == 'trims') {
            return array_except($rules, 'requisition_id');
        }

        return $rules;
    }

}
