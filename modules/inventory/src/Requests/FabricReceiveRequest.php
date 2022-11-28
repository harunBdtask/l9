<?php

namespace SkylarkSoft\GoRMG\Inventory\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use SkylarkSoft\GoRMG\Inventory\Rules\FabricReceiveQtyRule;

class FabricReceiveRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $bookingBasis = [
            'pi_basis',
            'booking_basis',
            'independent'
        ];

        return [
            'factory_id' => 'required',
            'receive_date' => 'required|date',
            'store_id' => 'required',
            'receive_basis' => ['required', Rule::in($bookingBasis)],
            'receivable_type' => 'required',
            'receivable_id' => request()->get('receivable_type') !== 'independent' ? 'required' : '',
            'dyeing_source' => ['nullable', Rule::in(['in_house', 'out_bound'])],
            // 'dyeing_supplier_type'            => 'required',
//            'dyeing_supplier_id' => 'required',
            'receive_challan' => 'nullable|string|max:30',
            'po_no' => 'nullable|array',
            'grey_issue_challan' => 'nullable|string|max:10',
            'lc_sc_no' => 'nullable|string|max:30',
            'exchange_rate' => 'nullable|numeric',
            'details.*.unique_id' => 'required|string|max:30',
            'details.*.buyer_id' => 'required',
            'details.*.style_id' => 'required',
            'details.*.style_name' => 'required|string|max:60',
            'details.*.batch_no' => 'required|string|max:60',
            'details.*.gmts_item_id' => 'required',
            'details.*.body_part_id' => 'required',
            'details.*.fabric_composition_id' => 'required',
            'details.*.construction' => 'required|string|max:255',
            'details.*.fabric_description' => 'required|string|max:255',
            'details.*.dia' => 'required',
            'details.*.ac_dia' => 'required',
            'details.*.gsm' => 'required|string|max:10',
            'details.*.ac_gsm' => 'required|string|max:10',
            'details.*.dia_type' => 'required',
            'details.*.ac_dia_type' => 'required',
            'details.*.color_id' => 'required',
            'details.*.contrast_color_id' => 'nullable|array',
            'details.*.uom_id' => 'required',
            'details.*.receive_qty' => ['required', 'numeric', new FabricReceiveQtyRule()],
            'details.*.rate' => 'required|numeric',
            'details.*.amount' => 'required|numeric',
            'details.*.reject_qty' => 'nullable|numeric',
            'details.*.fabric_shade' => 'nullable|string|max:5',
            'details.*.no_of_roll' => 'required|nullable|numeric',
            'details.*.grey_used' => 'nullable|string|max:5',
            //            'details.*.store_id' => 'required',
            'details.*.floor_id' => 'required',
            'details.*.room_id' => 'required',
            'details.*.rack_id' => 'required',
            'details.*.shelf_id' => 'required',

        ];
    }

    public function messages(): array
    {
        return [
            'factory_id.required' => 'Company is required!',
            'receive_date.required' => 'Receive Date is required!',
            'receive_date.date' => 'Must be valid date!',
            'store_id.required' => 'Store is required!',
            'receive_basis.required' => 'Receive Basis is required!',
            'details.*.unique_id.required' => 'Unique id is required',
            'details.*.buyer_id.required' => 'Buyer name is required',
            'details.*.style_id.required' => 'Style Id is required',
            'details.*.style_name.required' => 'Style name is required',
            'details.*.batch_no.required' => 'Batch no is required',
            'details.*.gmts_item_id.required' => 'Gmts item name no is required',
            'details.*.body_part_id.required' => 'Body part no is required',
            'details.*.receive_qty.required' => 'Receive Qty is required',
            'details.*.receive_qty.numeric' => 'Receive Qty must be a number',
        ];
    }
}
