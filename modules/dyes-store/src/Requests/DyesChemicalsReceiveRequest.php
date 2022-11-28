<?php

namespace SkylarkSoft\GoRMG\DyesStore\Requests;

use Illuminate\Foundation\Http\FormRequest;

class DyesChemicalsReceiveRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        return [
            "supplier_id" => "required|not_in:0",
            "receive_date" => "required",
        ];
    }

    /**
     * @return string[]
     */
    public function messages(): array
    {
        return [
            "supplier_id.required" => "Supplier name field is required",
            "supplier_id.not_in" => "The selected supplier name is invalid",
            "receive_date.required" => "Receive date is required",
        ];
    }
}
