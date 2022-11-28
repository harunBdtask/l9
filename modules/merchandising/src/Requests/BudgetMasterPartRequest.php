<?php

namespace SkylarkSoft\GoRMG\Merchandising\Requests;

use Illuminate\Foundation\Http\FormRequest;

class BudgetMasterPartRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'buyer_id' => 'required',
            'order_id' => 'required',
            'purchase_order_id' => 'required',
            'budget_basis' => 'required',
            "fabric" => 'required',
            'trims_accessories' => 'required',
            'others' => 'required',
            'remarks' => 'nullable|max:191',
        ];
    }
}
