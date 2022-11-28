<?php


namespace SkylarkSoft\GoRMG\SystemSettings\Requests;

use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Http\FormRequest;

class BodyPartRequest extends FormRequest
{
    public function authorize() : bool
    {
        return Auth::check();
    }

    public function rules() : array
    {
        return [
            'name' => "required|unique:body_parts,name," . $this->segment(2),
            'short_name' => "required",
            'entry_page' => 'nullable',
            'type' => 'required',
            'status' => 'required',
        ];
    }
}
