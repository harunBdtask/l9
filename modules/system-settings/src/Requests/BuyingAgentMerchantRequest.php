<?php

namespace SkylarkSoft\GoRMG\SystemSettings\Requests;

use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Http\FormRequest;

class BuyingAgentMerchantRequest extends FormRequest
{
    public function authorize() : bool
    {
        return Auth::check();
    }

    public function messages() : array
    {
        return [
            'buying_agent_id.required' => 'Buying Agent is required.',
            'buying_agent_merchant_name.required' => 'Buying Agent name is required.',
        ];
    }

    public function rules() : array
    {
        return [
            'buying_agent_id' => 'required',
            'buying_agent_merchant_name' => "required|not_regex:/([^\w\d\s&'.\-\_\)\(\/])+/i|unique:buying_agent_merchants,buying_agent_merchant_name," . $this->segment(2) . ',id,deleted_at,NULL',
        ];
    }
}
