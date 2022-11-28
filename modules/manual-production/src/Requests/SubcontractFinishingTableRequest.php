<?php

namespace SkylarkSoft\GoRMG\ManualProduction\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use SkylarkSoft\GoRMG\ManualProduction\Rules\UniqueSubcontractFinishingTableNameRule;

class SubcontractFinishingTableRequest extends FormRequest
{
    public function authorize(): bool
    {
        return Auth::check();
    }

    public function messages()
    {
        return [
            'required' => 'Required field',
            'integer' => 'Integer field',
            'min' => 'Negative value not allowed',
        ];
    }

    public function rules(): array
    {
        return [
            'subcontract_factory_profile_id' => 'required|integer',
            'subcontract_finishing_floor_id' => 'required|integer',
            'table_name' => ['required', new UniqueSubcontractFinishingTableNameRule()],
            'responsible_person' => 'required',
            'sorting' => 'required|integer',
        ];
    }
}
