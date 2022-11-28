<?php

namespace SkylarkSoft\GoRMG\Merchandising\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use SkylarkSoft\GoRMG\Merchandising\Rules\Orders\CheckBuyerIdForBundleCards;
use SkylarkSoft\GoRMG\Merchandising\Rules\Orders\CheckFactoryIdForBundleCards;
use SkylarkSoft\GoRMG\Merchandising\Rules\Orders\CheckStyleNameForBundleCards;

class OrderFormRequest extends FormRequest
{
    public function authorize(): bool
    {
        return Auth::check();
    }

    public function messages(): array
    {
        return [

        ];
    }

    public function rules(): array
    {
        return [
            'factory_id' => ['required', new CheckFactoryIdForBundleCards()],
            'buyer_id' => ['required', new CheckBuyerIdForBundleCards()],
            'style_name' => [
                'required',
                Rule::unique('orders', 'style_name')
                    ->ignore(request()->input('id'))
                    ->where("deleted_at", null),
                // new CheckStyleNameForBundleCards(),
            ],
            'order_uom_id' => 'required',
            'smv' => 'required',
        ];
    }
}
