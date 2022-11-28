<?php

namespace SkylarkSoft\GoRMG\TrimsStore\Requests\V3\TrimsStoreReceive;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class TrimsStoreReceiveDetailFormRequest extends FormRequest
{
    public function authorize(): bool
    {
        return Auth::check();
    }

    public function rules(): array
    {
        return [
            'receive_basis_id' => 'required',
            'buyer_id' => 'required',
            'style_id' => 'required',
            'po_numbers' => 'required',
            'booking_no' => 'required',
            'garments_item_id' => 'required',
            'item_id' => 'required',
            'supplier_id' => 'required',
            'receive_qty' => 'required',
            'uom_id' => 'required',
            'rate' => 'required',
            'amount' => 'required',
            'floor_id' => 'required',
            'room_id' => 'required',
            'rack_id' => 'required',
            'shelf_id' => 'required',
            'bin_id' => 'required',
        ];
    }
}
