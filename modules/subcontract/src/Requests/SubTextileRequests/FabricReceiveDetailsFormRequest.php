<?php

namespace SkylarkSoft\GoRMG\Subcontract\Requests\SubTextileRequests;

use Illuminate\Foundation\Http\FormRequest;
use SkylarkSoft\GoRMG\Subcontract\Rules\SubTextileRules\QtyRule;
use SkylarkSoft\GoRMG\Subcontract\Rules\SubTextileRules\ReceiveQtyRule;
use SkylarkSoft\GoRMG\Subcontract\Rules\SubTextileRules\ReceiveReturnQtyRule;

class FabricReceiveDetailsFormRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check();
    }

    public function rules(): array
    {
        return [
            'total_roll' => 'required',
            'receive_qty' => ['required', new QtyRule(), new ReceiveQtyRule()],
            'receive_return_qty' => [
                'sometimes',
                'numeric',
                'max:' . (int) request()->input('receive_qty'),
                new ReceiveReturnQtyRule(),
            ],
        ];
    }

    public function prepareForValidation()
    {
        $this->merge([
            'receive_return_qty' => empty(request()->input('receive_return_qty'))
                ? 0 : request()->input('receive_return_qty'),
        ]);
    }
}
