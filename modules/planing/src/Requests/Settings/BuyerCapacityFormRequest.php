<?php

namespace SkylarkSoft\GoRMG\Planing\Requests\Settings;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class BuyerCapacityFormRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'factory_id' => ['required'],
            'buyer_id' => ['required'],
            'month' => ['required'],
            'year' => ['required'],
            'capacity' => ['required'],
        ];
    }
}
