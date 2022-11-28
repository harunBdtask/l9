<?php

namespace SkylarkSoft\GoRMG\Merchandising\Requests;

use Illuminate\Foundation\Http\FormRequest;

class BudgetFabricComponentRequest extends FormRequest
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
        $rules['order_id'] = 'required';
        $rules['purchase_order_id'] = 'required';

        if (request()->dyeing_details == 1 && request()->knitting != 1 && request()->yarn != 1) {
            $rules['dyeing_part_fabric_gsm.*'] = 'required';
            $rules['dyeing_part_yarn_count.*'] = 'required';
            $rules['dyeing_part_dyeing_cost.*'] = 'required';
            $rules['dyeing_part_aop_cost.*'] = 'required';
            $rules['dyeing_part_peached_cost.*'] = 'required';
            $rules['dyeing_part_brushed_cost.*'] = 'required';
            $rules['dyeing_part_finishing_cost.*'] = 'required';
            $rules['dyeing_part_total_cost.*'] = 'required';
        }

        if (request()->knitting == 1 && request()->yarn == 1 && request()->dyeing_details == 1) {
            $rules['yarn_part_yarn_count.*'] = 'required';
            $rules['yarn_part_yarn_unit_price.*'] = 'required';

            /* knitting part */
            $rules['knitting_part_fabric_gsm.*'] = 'required';
            $rules['knitting_part_yarn_count.*'] = 'required';
            $rules['knitting_part_knitting_qty.*'] = 'required';
            $rules['knitting_part_knitting_unit_price.*'] = 'required';
            $rules['knitting_part_knitting_total.*'] = 'required';

            /* dyeing part */
            $rules['dyeing_part_fabric_gsm.*'] = 'required';
            $rules['dyeing_part_yarn_count.*'] = 'required';
            $rules['dyeing_part_dyeing_cost.*'] = 'required';
            $rules['dyeing_part_aop_cost.*'] = 'required';
            $rules['dyeing_part_peached_cost.*'] = 'required';
            $rules['dyeing_part_brushed_cost.*'] = 'required';
            $rules['dyeing_part_finishing_cost.*'] = 'required';
            $rules['dyeing_part_total_cost.*'] = 'required';
        }
        if (request()->knitting == 1 && request()->yarn == 1 && request()->dyeing_details != 1) {
            $rules['yarn_part_yarn_count.*'] = 'required';
            $rules['yarn_part_yarn_unit_price.*'] = 'required';

            /* knitting part */
            $rules['knitting_part_fabric_gsm.*'] = 'required';
            $rules['knitting_part_yarn_count.*'] = 'required';
            $rules['knitting_part_knitting_qty.*'] = 'required';
            $rules['knitting_part_knitting_unit_price.*'] = 'required';
            $rules['knitting_part_knitting_total.*'] = 'required';
        }
        if (request()->weaving == 1) {
            $rules['woven_source.*'] = 'required';
            $rules['woven_fabric_description.*'] = 'required';
            $rules['woven_fabric_composition.*'] = 'required';
            $rules['woven_fabric_type.*'] = 'required';
            $rules['woven_gsm.*'] = 'required';
            $rules['woven_required_dia.*'] = 'required';
            $rules['woven_fabric_required_qty.*'] = 'required';
            $rules['woven_uom.*'] = 'required';
            $rules['woven_unit_price.*'] = 'required';
            $rules['woven_total_amount.*'] = 'required';
            if (request()->woven_composition_fabric_id) {
                foreach (request()->woven_composition_fabric_id as $key => $val) {
                    if ($val == null) {
                        $rules['woven_fabric_composition.' . $key] = 'required|unique:fabric_composition,yarn_composition';
                    }
                }
            }
        }

        return $rules;
    }
}
