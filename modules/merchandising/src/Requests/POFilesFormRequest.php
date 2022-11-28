<?php


namespace SkylarkSoft\GoRMG\Merchandising\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class POFilesFormRequest extends FormRequest
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
            "style" => "required",
            "flag" => "required",
            'po_no' => [
                'required',
                Rule::unique('po_files')
                    ->where("deleted_at", null),
            ],
            "file" => "required|mimes:pdf",
        ];
    }

    public function messages(): array
    {
        return [
            "po_no.unique" => "Pdf already uploaded for this po no.",
        ];
    }
}
