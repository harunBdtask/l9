<?php

namespace SkylarkSoft\GoRMG\Inventory\Requests\TrimsStore\TrimsStoreReceive;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use SkylarkSoft\GoRMG\Inventory\Rules\TrimsStore\ReceiveQtyRule;

class TrimsStoreReceiveDetailFormRequest extends FormRequest
{
    public function authorize(): bool
    {
        return Auth::check();
    }

    public function rules(): array
    {
        return [
            'trims_store_receive_id' => 'required',
            'factory_id' => 'required',
            'store_id' => 'required',
            'current_date' => 'required',
            'item_id' => 'required',
//            'receive_qty' => ['required', new ReceiveQtyRule()],
            'receive_qty' => 'required',
            'receive_date' => 'required',
            'uom_id' => 'required',
            'rate' => 'required',
        ];
    }
}
