<?php

namespace SkylarkSoft\GoRMG\DyesStore\Requests;

use Illuminate\Foundation\Http\FormRequest;
use SkylarkSoft\GoRMG\DyesStore\Rules\DsReceiveReturnQtyRule;

class DsReceiveReturnRequest extends FormRequest
{
    /**
     * @return bool
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * @return string[]
     */
    public function rules(): array
    {
        return [
            "supplier_id" => "required|not_in:0",
            "receive_id" => "required",
            "challan_no" => "required",
            "return_date" => "required",
            "details.*.return_qty" => ["required", new DsReceiveReturnQtyRule()],
        ];
    }
}
