<?php

namespace SkylarkSoft\GoRMG\Inventory\Requests\TrimsStore\TrimsStoreMrr;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class TrimsStoreMrrFormRequest extends FormRequest
{
    public function authorize(): bool
    {
        return Auth::check();
    }

    public function rules(): array
    {
        return [
            'booking_no' => 'required',
            'trims_store_receive_id' => 'required',
            'store_id' => 'required',
            'challan_no' => 'required',
        ];
    }
}
