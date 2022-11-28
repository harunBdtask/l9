<?php

namespace SkylarkSoft\GoRMG\SystemSettings\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use SkylarkSoft\GoRMG\SystemSettings\Rules\UniqueGuideOrFolder;

class GuideOrFolderRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return Auth::check();
    }

    /**
    * Get the validation messages that apply to the erroneous request.
    *
    * @return bool
    */
    public function messages()
    {
        return [
            'name.required' => 'Name is required.',
        ];
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'name' => ['required',"not_regex:/([^\w\d\s&'])+/i",'max:70', new UniqueGuideOrFolder()],
        ];
    }
}
