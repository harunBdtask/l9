<?php

namespace SkylarkSoft\GoRMG\Finishingdroplets\Requests;

use Illuminate\Foundation\Http\FormRequest;

class FinishingTargetRequest extends FormRequest
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
    public function rules()
    {
        return [
            'production_date' => 'after:yesterday|before:tomorrow',
            'finishing_floor_id' => 'required'
        ];
    }
}
