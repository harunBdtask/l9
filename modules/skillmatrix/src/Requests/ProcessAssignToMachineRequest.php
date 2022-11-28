<?php

namespace SkylarkSoft\GoRMG\Skillmatrix\Requests;

use Illuminate\Foundation\Http\FormRequest;
use SkylarkSoft\GoRMG\Skillmatrix\Rules\CheckMachineWiseUniqueProcess;
use SkylarkSoft\GoRMG\Skillmatrix\Rules\UniqueMachineWiseProcess;

class ProcessAssignToMachineRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return auth()->check();
    }

    /**
     * Get the validation messages that apply to the erroneous request.
     *
     * @return string[]
     */
    public function messages(): array
    {
        return [
            'sewing_machine_id.required' => 'This field is required.',
            'sewing_process_id.*.required' => 'This field is required.',
        ];
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        return [
            'sewing_machine_id' => ['required', new CheckMachineWiseUniqueProcess()],
            'sewing_process_id.*' => ['required', new UniqueMachineWiseProcess()],
        ];
    }
}
