<?php

namespace SkylarkSoft\GoRMG\SystemSettings\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class PoFileIssueRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize(): bool
    {
        return auth()->check();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        return [
            "buyer_id" => "required",
            "issue" => [
                'required',
                Rule::unique('po_files_issue')
                    ->where('buyer_id', $this->buyer_id),
            ],
        ];
    }

    /**
     * @return array
     */
    public function messages(): array
    {
        return [
            "issue.unique" => "Issue already exists for this buyer.",
        ];
    }
}
