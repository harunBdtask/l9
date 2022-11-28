<?php

namespace SkylarkSoft\GoRMG\Dyeing\Requests\Compactor;

use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Http\FormRequest;

class CompactorSearchRequest extends FormRequest
{

    public function authorize(): bool
    {
        return Auth::check();
    }

    public function rules(): array
    {
        return [
            'type' => 'required',
            'factory_id' => 'required',
            'buyer_id' => 'required',
            'batch_id' => 'required_if:type,1',
            'order_id' => 'required_if:type,2',
        ];
    }

}
