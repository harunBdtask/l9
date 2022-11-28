<?php

namespace SkylarkSoft\GoRMG\Approval\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PermissionRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            '*.factory_id' => 'required|not_in:0',
            '*.page_name' => 'required',
            '*.priority' => [
                'required',
//                new PriorityUniqueRule()
            ],
            '*.user_id' => 'required|not_in:0',
            '*.buyer_ids' => 'required',
            '*.alternative_user_id' => 'required|not_in:0',
        ];
    }
}
