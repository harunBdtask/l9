<?php

namespace SkylarkSoft\GoRMG\SystemSettings\Requests;

use Illuminate\Foundation\Http\FormRequest;
use SkylarkSoft\GoRMG\SystemSettings\Rules\ItemGroupAssignRules;

class ItemGroupAssign extends FormRequest
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
    public function messages()
    {
        return [
//            'factory_id' => 'Factory field is required.',
            'item_id' => 'Item Group field is required.',
            'item_group_id' => 'Item Group Description is required.',
        ];
    }

    public function rules()
    {
        return [
            'item_id' => ['required', new ItemGroupAssignRules()],
//            'item_id' => ['required'],
            'item_group_id' => ['required'],
//            'factory_id' => ['required']
        ];
    }
}
