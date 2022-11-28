<?php

namespace SkylarkSoft\GoRMG\HR\Requests;

use Illuminate\Foundation\Http\FormRequest;
use SkylarkSoft\GoRMG\HR\Rules\UniqueDepartment;

class DepartmentRequest extends FormRequest
{

    public function authorize()
    {
        return true;
    }


    public function rules()
    {
        return [
            'name' => ['required', new UniqueDepartment()],
        ];
    }
}
