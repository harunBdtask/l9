<?php

namespace SkylarkSoft\GoRMG\DyesStore\Requests;

use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;
use SkylarkSoft\GoRMG\DyesStore\Models\DsStoreModel;

class DsStoreRequest extends FormRequest
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
        return [
            "name" => ["required","not_regex:/([^\w\d\s&'.\-\_\)\(\/])+/i", Rule::unique('ds_stores')
                ->whereNot('id',$this->route('store'))],
            "code" => ["required", Rule::unique('ds_stores')
                ->whereNot('id',$this->route('store'))],
            "sym" => ["required", Rule::unique('ds_stores','sym')
                ->whereNot('id',$this->route('store'))],
        ];
    }
}
