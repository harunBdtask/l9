<?php

namespace SkylarkSoft\GoRMG\Knitting\Requests;

use Illuminate\Foundation\Http\FormRequest;

class KnittingProductionRollFormRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check();
    }

    public function rules(): array
    {
        return [
            'shift_id' => 'required',
            'operator_id' => 'required',
            'roll_weight' => 'required',
            'production_datetime' => 'required',
            'collar_cuff_details.*.production_qty' => 'sometimes|required'
        ];
    }
}
