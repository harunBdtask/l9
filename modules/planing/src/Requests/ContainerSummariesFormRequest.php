<?php

namespace SkylarkSoft\GoRMG\Planing\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ContainerSummariesFormRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            '*.container_id' => 'required',
            '*.ex_factory_date' => 'required',
        ];
    }
}
