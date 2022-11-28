<?php


namespace SkylarkSoft\GoRMG\Merchandising\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ShortFabricBookingRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function messages()
    {
        return [

        ];
    }

    public function rules()
    {
        return [
            'factory_id' => 'required',
            'buyer_id' => 'required',
            'booking_date' => 'required',
            'fabric_source' => 'required',
            'delivery_date' => 'required',
            'currency_id' => 'required',
            'source' => 'required',
            'level' => 'required',
            'pay_mode' => 'required',
            'supplier_id' => 'required',
        ];
    }
}
