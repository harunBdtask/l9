<?php


namespace SkylarkSoft\GoRMG\Merchandising\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class POExcelFileFormRequest extends FormRequest
{
    /**
     * @return bool
     */
    public function authorize(): bool
    {
        return auth()->check();
    }

    /**
     * @return array
     */
    public function rules(): array
    {
        return [
            "buyer_id" => "required",
            // "style" => "required",
            "file" => "required|mimes:csv,xlsx,xls",
        ];
    }
}
