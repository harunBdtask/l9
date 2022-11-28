<?php

namespace SkylarkSoft\GoRMG\Subcontract\Requests\DyeingProcessRequests\DyeingRecipeRequests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class RecipeFormRequest extends FormRequest
{
    public function authorize(): bool
    {
        return Auth::check();
    }

    public function rules(): array
    {
        return [
            'factory_id' => 'required',
            'supplier_id' => 'required',
            'batch_no' => 'required',
//            'batch_no' => [
//                'required',
//                Rule::unique('sub_dyeing_recipes', 'batch_no')
//                    ->ignore($this->route('dyeingRecipe'))
//                    ->whereNull('deleted_at'),
//            ],
            'batch_id' => 'required',
            'liquor_ratio' => 'required',
            'total_liq_level' => 'required',
            'shift_id' => 'required',
            'recipe_date' => 'required',
        ];
    }

    public function messages(): array
    {
        return [
            'factory_id.required' => 'This factory field is required',
            'supplier_id.required' => 'This supplier field is required',
            'shift_id.required' => 'This shift field is required',
        ];
    }
}
