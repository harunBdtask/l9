<?php

namespace SkylarkSoft\GoRMG\Inventory\Requests;

use Illuminate\Foundation\Http\FormRequest;

class TrimsOrderToOrderTransferRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'factory_id' => 'required',
            'transfer_date' => 'required|date',
            'challan_no' => 'required',
            'from_order.from_store' => 'required',
            'from_order.po_no' => 'required',
            // 'from_order.po_qty' => 'required',
            'from_order.buyer_name' => 'required',
            'from_order.style_name' => 'required',
            'from_order.item_name' => 'required',
            'from_order.ship_date' => 'required',
            'from_order.transfer_qty' => 'required|not_in:0',
            'to_order.to_store' => 'required',
            'to_order.po_no' => 'required',
            // 'to_order.po_qty' => 'required',
            'to_order.buyer_name' => 'required',
            'to_order.style_name' => 'required',
            'to_order.item_id' => 'required',
            'to_order.ship_date' => 'required',
            'to_order.uom_id' => 'required',
        ];
    }

    public function messages(): array
    {
        return [
            'factory_id' => 'This factory name field is required',
        ];
    }
}