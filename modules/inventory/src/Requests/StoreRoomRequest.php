<?php

namespace SkylarkSoft\GoRMG\Inventory\Requests;

use Illuminate\Foundation\Http\FormRequest;
use SkylarkSoft\GoRMG\Inventory\Rules\UniqueStoreRoomRule;

class StoreRoomRequest extends FormRequest
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
            'name' => ['required', new UniqueStoreRoomRule()],
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