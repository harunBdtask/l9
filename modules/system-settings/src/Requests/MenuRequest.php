<?php

namespace SkylarkSoft\GoRMG\SystemSettings\Requests;

use Illuminate\Foundation\Http\FormRequest;
use SkylarkSoft\GoRMG\SystemSettings\Rules\UniqueMenu;

class MenuRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return \Auth::check();
    }
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        return [
            // 'menu_name' => ['required', new UniqueMenu],
            // 'menu_url' => ['required', new UniqueMenu],
            'menu_name' => ['required',"not_regex:/([^\w\d\s&'.\-\_\)\(\/])+/i",new UniqueMenu()],
            'menu_url' => 'required',
            'module_id' => 'required',
            'left_menu' => 'required',
            'sort' => 'nullable|numeric|max:150',
        ];
    }
    /**
     * Get the validation messages that apply to the erroneous request.
     *
     * @return bool
     */
    public function messages()
    {
        return [
            'menu_name.required' => 'Menu name is required.',
            'menu_url.required' => 'Menu url is required.',
            'module_id.required' => 'Module is required.',
            'left_menu.required' => 'Left Menu field is required.',
            'sort' => 'Left Menu field is required.',
        ];
    }


}
