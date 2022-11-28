<?php

namespace SkylarkSoft\GoRMG\Dyeing\Requests\Recipe;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class DyeingRecipeDetailFormRequest extends FormRequest
{

    public function authorize(): bool
    {
        return Auth::check();
    }

    public function rules(): array
    {
        return [
            'dyeing_recipe_id' => 'required',
            'recipe_operation_id' => 'required',
            'recipe_function_id' => 'required',
            'item_id' => 'required',
            'unit_of_measurement_id' => 'required',
            'percentage' => 'required_if:g_per_ltr,==,null',
            'g_per_ltr' => 'required_if:percentage,==,null',
        ];
    }

}
