<?php

namespace SkylarkSoft\GoRMG\Inventory\Requests\TrimsStore\TrimsStoreMrr;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class TrimsStoreMrrDetailFormRequest extends FormRequest
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
            'item_description' => 'required',
            'uom_id' => 'required',
        ];
    }
}
