<?php

namespace SkylarkSoft\GoRMG\Inventory\Requests;

use Illuminate\Foundation\Http\FormRequest;
use SkylarkSoft\GoRMG\Inventory\Rules\YarnTransferQtyRule;
use SkylarkSoft\GoRMG\Inventory\Services\YarnStockSummaryService;

class YarnTransferDetailRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check();
    }

    public function messages(): array
    {
        return [
            'required' => 'Required'
        ];
    }

    public function rules(): array
    {
        return [
            'yarn_count_id'       => 'required',
            'yarn_composition_id' => 'required',
            'yarn_type_id'        => 'required',
            'yarn_brand'          => 'required',
            'uom_id'              => 'required',
            'yarn_lot'            => 'required',
            'store_id'            => 'required',
            'transfer_qty'        => ['required', new YarnTransferQtyRule],
            'rate'                => 'required',
        ];
    }
}