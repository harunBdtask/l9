<?php

namespace SkylarkSoft\GoRMG\HR\Requests;

use Illuminate\Foundation\Http\FormRequest;
use SkylarkSoft\GoRMG\HR\Rules\UniqueSection;

class SectionRequest extends FormRequest
{

    public function authorize()
    {
        return true;
    }


    public function rules()
    {
        return [
            'name' =>  ['required', new UniqueSection()],
            'department_id' => 'required',
        ];
    }

    public function messages()
    {
        return [
            'department_id.required' => 'Department is required.',
        ];
    }
}
