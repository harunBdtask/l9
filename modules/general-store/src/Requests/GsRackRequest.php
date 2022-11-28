<?php

namespace SkylarkSoft\GoRMG\GeneralStore\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class GsRackRequest extends FormRequest
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
            "name" => ["required","not_regex:/([^\w\d\s&'])+/i", Rule::unique('gs_racks', 'name')->ignore($this->rack)->whereNull('deleted_at')],
        ];
    }
}
