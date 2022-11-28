<?php

namespace SkylarkSoft\GoRMG\Subcontract\Requests\DyeingProcessRequests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class SubDyeingBatchFormRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check();
    }

    public function rules(): array
    {
        return [
            'factory_id' => ['required'],
            'supplier_id' => ['required'],
            'batch_date' => ['required'],
            'sub_textile_order_ids' => [Rule::when(request()->has('id'), 'required')],
            'color_range_id' => ['required'],
            'batch_no' => [
                'required',
                Rule::unique('sub_dyeing_batches', 'batch_no')
                    ->ignore($this->route('dyeingBatch'))
                    ->whereNull('deleted_at'),
            ],
        ];
    }
}
