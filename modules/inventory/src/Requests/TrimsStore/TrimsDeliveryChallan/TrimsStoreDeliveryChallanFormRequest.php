<?php

namespace SkylarkSoft\GoRMG\Inventory\Requests\TrimsStore\TrimsDeliveryChallan;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class TrimsStoreDeliveryChallanFormRequest extends FormRequest
{
    public function authorize(): bool
    {
        return Auth::check();
    }

    public function rules(): array
    {
        return [
            'booking_nos' => 'required',
            'challan_no' => 'required',
            'challan_date' => 'required',
        ];
    }
}
