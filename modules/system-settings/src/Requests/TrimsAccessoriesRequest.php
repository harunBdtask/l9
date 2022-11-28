<?php


namespace SkylarkSoft\GoRMG\SystemSettings\Requests;

use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Http\FormRequest;

class TrimsAccessoriesRequest extends FormRequest
{
    public function authorize() : bool
    {
        return Auth::check();
    }

    public function messages() : array
    {
        return [
            'name.required' => 'Trims Accessories Item is required',
        ];
    }

    public function rules() : array
    {
        return [
            'name' => "required|not_regex:/([^\w\d\s&'.\-\_\)\(\/])+/i|unique:trims_accessories_item,name," . $this->segment(2),
        ];
    }
}
