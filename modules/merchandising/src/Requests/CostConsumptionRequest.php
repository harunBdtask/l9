<?php

namespace SkylarkSoft\GoRMG\Merchandising\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CostConsumptionRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $rules = [];
        $rules['buyer_id'] = 'required';
        $rules['agent_id'] = 'required';
        $rules['sample_ref_no'] = 'required';
        $rules['currency_id'] = 'required';

        $rules['finish_fab_cost.*'] = 'required';
        $rules['trims_accessories.*'] = 'required';
        $rules['cost_of_manufacturing.*'] = 'required';
        $rules['others_cost.*'] = 'required';
        $rules['profit_percentage.*'] = 'required';
        $rules['item_unit_cost.*'] = 'required';
        $rules['set_information.*'] = 'required';

        return $rules;
    }
}
