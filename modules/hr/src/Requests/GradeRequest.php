<?php

namespace SkylarkSoft\GoRMG\HR\Requests;

use Illuminate\Foundation\Http\FormRequest;
use SkylarkSoft\GoRMG\HR\Rules\UniqueGrade;

class GradeRequest extends FormRequest
{

    public function authorize(): bool
    {
        return auth()->check();
    }


    public function rules()
    {
        return [
            'name' => ['required', new UniqueGrade()],
        ];
    }
}
