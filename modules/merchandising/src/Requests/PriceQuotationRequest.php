<?php

namespace SkylarkSoft\GoRMG\Merchandising\Requests;

use Illuminate\Foundation\Http\FormRequest;
use SkylarkSoft\GoRMG\Merchandising\Models\PriceQuotation;

class PriceQuotationRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return auth()->check();
    }

    public function messages()
    {
        return [
            'factory_id.required' => 'This field is required',
            'buyer_id.required' => 'This field is required',
            'product_department_id.required' => 'This field is required',
            'style_name.required' => 'This field is required',
            'season_id.required' => 'This field is required',
            'style_uom.required' => 'This field is required',
            'costing_per.required' => 'This field is required',
            'quotation_date.required' => 'This field is required',
            'quotation_date.date' => 'This field is required a date',
            'offer_qty.numeric' => "Must be a number",
            'garment_item_val.required' => 'Required field',
            'item_ratio_val.required' => 'Required field',
            'smv_val.required' => 'Required field',

        ];
    }

    public function rules()
    {
        if ($this->is_approve == 1) {
            return [];
        }

        $rules = [
            'factory_id' => 'required',
            'buyer_id' => 'required',
            'product_department_id' => 'required',
            'style_name' => 'required',
            'season_id' => 'required',
            'style_uom' => 'required',
            'machine_line' => 'nullable|numeric',
            'prod_line_hr' => 'nullable|numeric',
            'sew_smv' => 'nullable|numeric',
            'sew_eff' => 'nullable|numeric',
            'cut_smv' => 'nullable|numeric',
            'cut_eff' => 'nullable|numeric',
            'costing_per' => 'required',
            'offer_qty' => 'nullable|numeric',
            'quotation_date' => 'required|date',
            'op_date' => 'nullable|date',
            'est_shipment_date' => 'nullable|date',
            'file' => 'nullable|max:2048|mimes:doc,docx,pdf,xls,xlsx',
            'image' => 'nullable|max:2048|mimes:jpeg,png,jpg,gif,svg',
            'files.*' => 'nullable|max:2048|mimes:doc,docx,pdf,xls,xlsx',
        ];
        if (! request()->has('garment_item_id')) {
            $rules['garment_item_val'] = 'required';
        }
        if (! request()->has('item_ratio')) {
            $rules['item_ratio_val'] = 'required';
        }
        if (! request()->has('smv')) {
            $rules['smv_val'] = 'required';
        }

        return $rules;
    }
}
