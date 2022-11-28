<?php

namespace SkylarkSoft\GoRMG\Inventory\Requests;

use Illuminate\Foundation\Http\FormRequest;
use SkylarkSoft\GoRMG\Inventory\Rules\UniqueStoreBinRule;

class StoreBinRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'factory_id' => 'required',
            'store_id' => 'required',
            'floor_id' => 'required',
            'room_id' => 'required',
            'rack_id' => 'required',
            'shelf_id' => 'required',
            'name' => ['required', new UniqueStoreBinRule()],
            'sequence' => 'required|integer|min:0',
        ];
    }

    public function messages(): array
    {
        return [
            'required' => 'Required',
            'integer' => 'Must be integer',
            'min' => 'Negative value not allowed',
        ];
    }
}