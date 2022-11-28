<?php

namespace SkylarkSoft\GoRMG\Merchandising\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SampleDevelopmentRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function messages()
    {
        return [
            'item_id.required' => 'Item field is required.',
            'agent_id.required' => 'Agent is required.',
            'recv_date.required' => 'Date field is required.',
            'buyer_id.required' => 'Buyer field is required.',
            'fabric_desc.required' => 'Fabric field is required.',
            'fabrication.required' => 'Artwork field is required.',
            'sample_ref_no.required' => 'Sample Ref. No field is required.',
        ];
    }

    public function rules()
    {
        $rules = [];
        $rules['agent_id'] = 'required';
        $rules['sample_ref_no'] = 'required';
        $rules['buyer_id'] = 'required';
        $rules['receive_date'] = 'required';
        $rules['team_leader'] = 'required';
        $rules['dealing_merchant'] = 'required';
        $rules['season'] = 'required';
        $rules['currency'] = 'required';
        $rules['item_id.*'] = 'required';
        $rules['fabric_description.*'] = 'required';
        $rules['unit_price.*'] = 'required';
        $rules['composition_fabric_id.*'] = 'required';
        $rules['item_description.*'] = 'required';
        $rules['gsm.*'] = 'required';

        return $rules;
    }
}
