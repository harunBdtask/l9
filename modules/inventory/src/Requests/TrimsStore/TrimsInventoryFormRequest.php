<?php

namespace SkylarkSoft\GoRMG\Inventory\Requests\TrimsStore;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class TrimsInventoryFormRequest extends FormRequest
{
    public function authorize(): bool
    {
        return Auth::check();
    }

    public function rules(): array
    {
        return [
            'booking_no' => 'required',
            'store_id' => 'required',
            'challan_no' => 'required',
            'challan_date' => 'required',
        ];
    }
}
