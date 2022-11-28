<?php


namespace SkylarkSoft\GoRMG\Merchandising\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SampleRequisitionRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function messages(): array
    {
        return [
            'required' => 'Required',
        ];
    }

    public function rules(): array
    {
        return [
            'sample_stage' => 'required',
            'req_date' => 'required',
            'style_name' => 'required',
            'factory_id' => 'required',
            'location' => 'required',
            'buyer_id' => 'required',
            'season_id' => 'required',
            'dealing_merchant_id' => 'required',
            'product_department_id' => 'required',
        ];
    }
}
