<?php

namespace SkylarkSoft\GoRMG\Dyeing\Requests\DyeingBatch;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class DyeingBatchRequest extends FormRequest
{

    public function authorize(): bool
    {
        return Auth::check();
    }

    public function rules(): array
    {
        return [
            'factory_id' => 'required',
            'buyer_id' => 'required',
            'sub_dyeing_unit_id' => 'required',
            'batch_date' => 'required',
            'color_range_id' => 'required',
            'batch_no' => [
                'required', Rule::unique('dyeing_batches', 'batch_no')
                    ->ignore($this->route('dyeingBatch'))
                    ->whereNull('deleted_at')
            ],
        ];
    }
}
