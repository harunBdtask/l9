<?php

namespace SkylarkSoft\GoRMG\Merchandising\Requests;

use Illuminate\Foundation\Http\FormRequest;
use SkylarkSoft\GoRMG\Merchandising\Rules\UniquePoColorSize;

class PurchaseOrderRequest extends FormRequest
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

    public function messages()
    {
        return [
            'ratio.*.required' => 'Composition is required',
            'incoterm_id.required' => 'Incoterm is required',
            'incoterm_place_id.required' => 'Incoterm Place is required.',
        ];
    }

    public function rules()
    {
        $rules = [
            'buyer_id' => 'required',
            'order_id' => 'required',
//            'po_no' => 'required|unique:purchase_orders,po_no,' . request()->id . ',id',
            'po_no' => 'required',
            'shipping_mode' => 'required',
            'packing_mode' => 'required',
            'po_quantity' => 'required',
            'ex_factory_date' => 'required',
            'order_uom' => 'required',
            'incoterm_id' => 'required',
            'incoterm_place_id' => 'required',
            'item_id_breakdown.*' => 'required',
            'fabric_description.*' => ['required', new UniquePoColorSize],
            'fabrication.*' => ['required', new UniquePoColorSize],
            'color_id.*' => ['required', new UniquePoColorSize],
            'size_id.*' => ['required', new UniquePoColorSize],
            'gsm.*' => ['required', new UniquePoColorSize],
            'quantity.*' => 'required',
            'color_name.*' => 'required',
            'color_type.*' => ['required', new UniquePoColorSize],
        ];

        if (request()->composition_fabric_id) {
            foreach (request()->composition_fabric_id as $key => $val) {
                if ($val == null) {
                    $rules['fabrication.' . $key] = 'required|unique:fabric_composition,yarn_composition';
                }
            }
        }

        if (request()->order_uom == 2) {
            $rules['ratio .*'] = 'required';
        }

        return $rules;
    }
}
