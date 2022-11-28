<?php

namespace SkylarkSoft\GoRMG\SystemSettings\Requests;

use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Http\FormRequest;

class ItemSubgroupRequest extends FormRequest
{
    public function authorize(): bool
    {
        return Auth::check();
    }

    public function rules(): array
    {
        return [
            'name' => [
                'required',
                "not_regex:/([^\w\d\s&'.\-\_\)\(\/])+/i",
                Rule::unique('item_subgroups')
                    ->whereNull('deleted_at')
                    ->ignore(request()->route('itemSubgroup')),
            ],
        ];
    }
}
