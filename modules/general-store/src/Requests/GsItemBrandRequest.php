<?php

namespace SkylarkSoft\GoRMG\GeneralStore\Requests;

use Illuminate\Foundation\Http\FormRequest;

class GsItemBrandRequest extends FormRequest
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
            "item_id" => "required",
            "brand_id" => "required"
        ];
    }
}