<?php

namespace SkylarkSoft\GoRMG\Dyeing\Requests\DyeingBatch;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class DyeingBatchMachineAllocationRequest extends FormRequest
{

    public function authorize(): bool
    {
        return Auth::check();
    }

    public function rules(): array
    {
        return [
            'machine_id' => ['required'],
            'distribution_qty' => ['required'],
        ];
    }

}
