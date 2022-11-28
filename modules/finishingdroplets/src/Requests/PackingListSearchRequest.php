<?php

namespace SkylarkSoft\GoRMG\Finishingdroplets\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PackingListSearchRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'po_id' => 'required',
            'packing_assortment' => 'required'
        ];
    }

    public function authorize(): bool
    {
        return auth()->check();
    }
}
