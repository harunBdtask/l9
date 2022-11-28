<?php

namespace SkylarkSoft\GoRMG\Finishingdroplets\Requests;

use Illuminate\Foundation\Http\FormRequest;

class GarmentPackingProductionRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'production_date' => 'required'
        ];
    }

    public function authorize(): bool
    {
        return auth()->check();
    }
}
