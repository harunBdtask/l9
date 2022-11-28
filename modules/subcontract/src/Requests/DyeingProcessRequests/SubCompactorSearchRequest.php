<?php

namespace SkylarkSoft\GoRMG\Subcontract\Requests\DyeingProcessRequests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class SubCompactorSearchRequest extends FormRequest
{
    /**
     * @return string[]
     */
    public function rules(): array
    {
        return [
            'type' => 'required',
            'factory_id' => 'required',
            'supplier_id' => 'required',
            'batch_id' => 'required_if:type,1',
            'order_id' => 'required_if:type,2',
        ];
    }

    /**
     * @return bool
     */
    public function authorize(): bool
    {
        return Auth::check();
    }
}
