<?php

namespace SkylarkSoft\GoRMG\GeneralStore\Requests;

use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;

class GsItemCategoryRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            "name" => ["required","not_regex:/([^\w\d\s&'.\-\_\)\(\/])+/i", Rule::unique('gs_inv_items_category')->ignore($this->items_category)->whereNull('deleted_at')],
            "code" => ["required","not_regex:/([^\w\d\s&'.\-\_\)\(\/])+/i", Rule::unique('gs_inv_items_category')->ignore($this->items_category)->whereNull('deleted_at')],
        ];
    }
}
