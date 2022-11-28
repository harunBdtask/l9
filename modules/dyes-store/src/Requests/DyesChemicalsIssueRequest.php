<?php


namespace SkylarkSoft\GoRMG\DyesStore\Requests;


use Illuminate\Foundation\Http\FormRequest;

class DyesChemicalsIssueRequest extends FormRequest
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
            "delivery_date" => "required",
            "customer_id" => "required|not_in:0",
            "store_id" => "required",
        ];
    }

    /**
     * @return string[]
     */
    public function messages(): array
    {
        return [
            'store_id' => 'The store field is required',
            'delivery_date.required' => 'Delivery date is required',
            'customer_id.required' => 'Customer name field is required',
            'customer_id.not_in' => 'The selected customer name is invalid',
        ];
    }
}
