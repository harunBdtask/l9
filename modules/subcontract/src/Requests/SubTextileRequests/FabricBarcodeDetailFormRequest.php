<?php

namespace SkylarkSoft\GoRMG\Subcontract\Requests\SubTextileRequests;

use Illuminate\Foundation\Http\FormRequest;
use SkylarkSoft\GoRMG\Subcontract\Rules\SubTextileRules\BarcodeQtyRule;

class FabricBarcodeDetailFormRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'factory_id' => 'required',
            'sub_grey_store_receive_id' => 'required',
            'sub_grey_store_receive_detail_id' => 'required',
            'sub_grey_store_id' => 'required',
            'supplier_id' => 'required',
            'sub_textile_order_id' => 'required',
            'sub_textile_order_detail_id' => 'required',
            'barcode_qty.*' => 'required',
            'barcode_qty' => [new BarcodeQtyRule()],
        ];
    }
}
