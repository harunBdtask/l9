<?php

namespace SkylarkSoft\GoRMG\Inventory\Requests\TrimsStore\TrimsDeliveryChallan;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use SkylarkSoft\GoRMG\Inventory\Rules\TrimsStore\DeliveryChallanIssueQtyRule;

class TrimsStoreDeliveryChallanDetailFormRequest extends FormRequest
{
    public function authorize(): bool
    {
        return Auth::check();
    }

    public function rules(): array
    {
        return [
            'factory_id' => 'required',
            'store_id' => 'required',
            'item_id' => 'required',
            'issue_qty' => ['required', new DeliveryChallanIssueQtyRule()],
            'issue_date' => 'required',
            'uom_id' => 'required',
        ];
    }
}
