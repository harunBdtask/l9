<?php

namespace SkylarkSoft\GoRMG\Dyeing\Requests\DyeingBatch;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class DyeingBatchDetailRequest extends FormRequest
{

    public function authorize(): bool
    {
        return Auth::check();
    }

    public function rules(): array
    {
        return [
            'stitch_length' => 'required',
            'batch_roll' => 'required',
            'batch_weight' => 'required',
        ];
    }
}
