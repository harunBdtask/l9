<?php

namespace SkylarkSoft\GoRMG\DyesStore\Requests;

use Illuminate\Foundation\Http\FormRequest;

class DsDepartmentRequest extends FormRequest
{
    /**
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
            "name" => "required",
        ];
    }
}
