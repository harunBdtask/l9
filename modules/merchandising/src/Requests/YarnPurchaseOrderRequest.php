<?php

namespace SkylarkSoft\GoRMG\Merchandising\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class YarnPurchaseOrderRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize(): bool
    {
        return Auth::check();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        return [
            'factory_id' => 'required',
            'buyer_id' => 'required',
            'supplier_id' => 'required',
            'wo_date' => 'required',
            'delivery_date' => 'required',
            'pay_mode' => 'required',
            'source' => 'required',
        ];
    }
}
