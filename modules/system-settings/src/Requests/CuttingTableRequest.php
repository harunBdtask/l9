<?php

namespace SkylarkSoft\GoRMG\SystemSettings\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use SkylarkSoft\GoRMG\SystemSettings\Rules\UniqueCuttingTable;

class CuttingTableRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize(): bool
    {
        return Auth::check();
    }

    /**
     * Get the validation messages that apply to the erroneous request.
     *
     * @return string[]
     */
    public function messages(): array
    {
        return [
            'cutting_floor_id.required' => 'Cutting floor no is required.',
            'table_no.required' => 'Cutting table no is required.',
            'code.max' => 'Table no length is at most 30 characters long.',
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
            'cutting_floor_id' => 'required',
            'table_no' => [
                'required',"not_regex:/([^\w\d\s&'])+/i",'max:30', new UniqueCuttingTable(),
            ],
        ];
    }
}
