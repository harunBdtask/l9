<?php

namespace SkylarkSoft\GoRMG\Subcontract\Requests\DyeingProcessRequests;

use Illuminate\Foundation\Http\FormRequest;

class SubDyeingSqueezerDetailSearchRequest extends FormRequest
{
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

    public function authorize(): bool
    {
        return true;
    }
}
