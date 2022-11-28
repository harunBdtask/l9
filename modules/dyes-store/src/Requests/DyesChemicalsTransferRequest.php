<?php

namespace SkylarkSoft\GoRMG\DyesStore\Requests;

use Illuminate\Foundation\Http\FormRequest;

class DyesChemicalsTransferRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        return [
            "from_store" => "required",
            "to_store" => "required|different:from_store",
            "trn_qty" => "required|not_in:0",
        ];
    }
}
