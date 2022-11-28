<?php

namespace SkylarkSoft\GoRMG\TimeAndAction\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UserWiseTaskPermissionRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'buyer_id' => 'required',
        ];
    }
}
