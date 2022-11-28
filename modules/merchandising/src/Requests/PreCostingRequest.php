<?php

namespace SkylarkSoft\GoRMG\Merchandising\Requests;

use Illuminate\Foundation\Http\FormRequest;
use SkylarkSoft\GoRMG\Merchandising\Rules\FileSize;

class PreCostingRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {

        return [
            'factory_id' => 'required',
            'buyer_id' => 'required',
            'season_id' => 'required',
            'style' => 'required',
            'customer' => 'required'
        ];
    }

    public function messages()
    {
        return [
          'factory_id.required' => 'Company is Required',
          'buyer_id.required' => 'Buyer is Required',
          'season_id.required' => 'Season is Required',
          'style.required' => 'Style is Required',
          'customer.required' => 'Customer is Required',
        ];
    }
}