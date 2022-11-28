<?php

namespace SkylarkSoft\GoRMG\Dyeing\Requests;

use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Http\FormRequest;

class DyeingGoodsDeliveryFormRequest extends FormRequest
{

    public function authorize(): bool
    {
        return Auth::check();
    }

    public function rules(): array
    {
        return [
            'factory_id' => 'required',
            'buyer_id' => 'required',
            'delivery_date' => 'required',
            'dyeing_goods_delivery_details' => 'required',
            'dyeing_goods_delivery_details.*.total_roll' => 'required|numeric|gte:0',
            'dyeing_goods_delivery_details.*.delivery_qty' => 'required|numeric|gte:0',
        ];
    }

}
