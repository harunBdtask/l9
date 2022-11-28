<?php

namespace SkylarkSoft\GoRMG\TrimsStore\Requests\V3\TrimsStoreReceive;

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
            'source_id' => 'required',
            'store_id' => 'required',
            'receive_basis_id' => 'required',
            'challan_no' => 'required',
            'receive_date' => 'required',
        ];
    }
}
