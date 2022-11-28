<?php

namespace SkylarkSoft\GoRMG\DyesStore\Requests;

use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;

class DsItemRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
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
        $this->merge(['barcode' => $this->barcode == 'yes']);

        $rules = [
            "name" => ["required", Rule::unique('ds_inv_items')->ignore($this->item)],
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
