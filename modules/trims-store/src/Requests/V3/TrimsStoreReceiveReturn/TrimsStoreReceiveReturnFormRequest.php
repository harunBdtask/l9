<?php

namespace SkylarkSoft\GoRMG\TrimsStore\Requests\V3\TrimsStoreReceiveReturn;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class TrimsStoreReceiveReturnFormRequest extends FormRequest
{
    public function authorize(): bool
    {
        return Auth::check();
    }

    public function rules(): array
    {
        return [
            'factory_id' => 'required',
            'returned_source_id' => 'required',
            'return_date' => 'required',
            'return_type_id' => 'required',
            'return_basis_id' => 'required',
            'store_id' => 'required',
        ];
    }
}
