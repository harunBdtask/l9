<?php


namespace SkylarkSoft\GoRMG\Merchandising\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class TechPackFilesFormRequest extends FormRequest
{
    /**
     * @return bool
     */
    public function authorize(): bool
    {
        return auth()->check();
    }

    public function rules(): array
    {
        return [
            "style" => [
                'required',
                Rule::unique('tech_pack_files')->where("deleted_at", null),
            ],
            'creeper_count' => 'required',
            'body_part_count' => 'required',
            "file" => "required|mimes:pdf,xlsx",
        ];
    }
}
