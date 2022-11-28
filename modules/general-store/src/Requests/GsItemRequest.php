<?php

namespace SkylarkSoft\GoRMG\GeneralStore\Requests;

use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;

class GsItemRequest extends FormRequest
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
        $this->merge(['barcode' => $this->barcode == 'yes']);

        $rules = [
            "name" => ["required","not_regex:/([^\w\d\s&'.\-\_\)\(\/])+/i", Rule::unique('gs_inv_items')->ignore($this->item)->whereNull('deleted_at')],
            'category_id' => 'required',
            'uom' => 'required',
            'store' => 'required',
            'abbr' => 'required|max:10'
        ];

        if ($this->barcode) {
            $rules = array_merge($rules, ['qty' => 'required|integer|min:1']);
        }

        return $rules;
    }
}
