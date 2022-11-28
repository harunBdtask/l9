<?php

namespace SkylarkSoft\GoRMG\Sewingdroplets\Requests;

use Illuminate\Foundation\Http\FormRequest;

class FactoryCapacityRequest extends FormRequest
{
    public function rules(): array
    {
        return [
//            '*.line_id' => ['required'],
//            '*.garments_item_id' => ['required'],
//            '*.smv' => ['required'],
//            '*.efficiency' => ['required'],
//            '*.operator_machine' => ['numeric'],
//            '*.helper' => ['numeric'],
//            '*.wh' => ['numeric'],
//            '*.capacity_pcs' => ['numeric'],
        ];
    }

    public function authorize(): bool
    {
        return auth()->check();
    }
}
