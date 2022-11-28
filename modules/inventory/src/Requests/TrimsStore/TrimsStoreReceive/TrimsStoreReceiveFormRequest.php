<?php

namespace SkylarkSoft\GoRMG\Inventory\Requests\TrimsStore\TrimsStoreReceive;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class TrimsStoreReceiveFormRequest extends FormRequest
{
    public function authorize(): bool
    {
        return Auth::check();
    }

    public function rules(): array
    {
        return [
            'factory_id' => 'required',
            'buyer_id' => 'required',
            'store_id' => 'required',
            'booking_id' => 'required',
            'booking_no' => 'required',
            'trims_inventory_id' => 'required',
            'challan_no' => 'required',
        ];
    }
}
