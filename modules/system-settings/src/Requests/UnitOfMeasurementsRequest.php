<?php

namespace SkylarkSoft\GoRMG\SystemSettings\Requests;

use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Http\FormRequest;

class UnitOfMeasurementsRequest extends FormRequest
{
    public function authorize() : bool
    {
        return Auth::check();
    }

    public function messages() : array
    {
        return [
            'unit_of_measurement.required' => 'UOM is required.',
        ];
    }

    public function rules() : array
    {
        return [
            'unit_of_measurement' => "required|not_regex:/([^\w\d\s&'.\-\_\)\(\/])+/i|unique:unit_of_measurements,unit_of_measurement," . $this->segment(2) . ',id',
        ];
    }
}
