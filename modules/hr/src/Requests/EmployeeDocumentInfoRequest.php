<?php

namespace SkylarkSoft\GoRMG\HR\Requests;

use Illuminate\Foundation\Http\FormRequest;

class EmployeeDocumentInfoRequest extends FormRequest
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
            'nid'                   => 'nullable|file|max:5120',
            'birth_certificate'     => 'nullable|file|max:5120',
            'photo'                 => 'nullable|file|max:5120',
            'character_certificate' => 'nullable|file|max:5120',
            'ssc_certificate'       => 'nullable|file|max:5120',
            'hsc_certificate'       => 'nullable|file|max:5120',
            'biodata'               => 'nullable|file|max:5120',
            'medical_certificate'   => 'nullable|file|max:5120',
            'signature'             => 'nullable|file|max:5120',
            'masters'               => 'nullable|file|max:5120',
            'hons'                  => 'nullable|file|max:5120',
            'others'                => 'nullable|file|max:5120',
        ];
    }
}
