<?php

namespace SkylarkSoft\GoRMG\SystemSettings\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ArchiveFileRequest extends FormRequest
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
     * Get the validation messages that apply to the erroneous request.
     *
     * @return array
     */
    public function messages(): array
    {
        return [
            'buyer_id.required' => 'Buyer field is required.',
            'file_names.*.required' => 'File Name field is required.',
        ];
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        return [
            'buyer_id' => 'required',
            'file_names' => 'required|array|min:1',
            'files' => 'required|array|min:1',
            'file_names.*' => 'required',
            'files.*' => 'required|max:2048',
        ];
    }
}
