<?php

namespace SkylarkSoft\GoRMG\WarehouseManagement\Requests;

use Illuminate\Foundation\Http\FormRequest;
use SkylarkSoft\GoRMG\WarehouseManagement\Rules\UniqueWarehouseRack;

class WarehouseRackRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return \Auth::check();
    }

    /**
     * Get the validation messages that apply to the erroneous request.
     *
     * @return bool
     */
    public function messages()
    {
        return [
            'name.required' => 'This field is required.',
            'warehouse_floor_id.required' => 'This field is required.',
        ];
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'name' => ['required', new UniqueWarehouseRack()],
            'warehouse_floor_id' => 'required',
            'capacity' => 'required|integer|min:1',
        ];
    }
}
