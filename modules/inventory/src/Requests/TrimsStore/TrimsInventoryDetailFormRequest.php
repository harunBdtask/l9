<?php

namespace SkylarkSoft\GoRMG\Inventory\Requests\TrimsStore;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class TrimsInventoryDetailFormRequest extends FormRequest
{
    public function authorize(): bool
    {
        return Auth::check();
    }

    public function rules(): array
    {
        return [
            'factory_id' => 'required',
            'receive_date' => 'required',
            'store_id' => 'required',
            'item_id' => 'required',
            'item_description' => 'required',
            'uom_id' => 'required',
            'receive_qty' => 'required',
        ];
    }
}
