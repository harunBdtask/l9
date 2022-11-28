<?php

namespace SkylarkSoft\GoRMG\Subcontract\Requests\DyeingProcessRequests\DyeingRecipeRequests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class RecipeDetailsFormRequest extends FormRequest
{
    public function authorize(): bool
    {
        return Auth::check();
    }

    public function rules(): array
    {
        return [
            'sub_dyeing_recipe_id' => 'required',
            'recipe_operation_id' => 'required',
//            'recipe_function_id' => 'required',
            'item_id' => 'required',
            'unit_of_measurement_id' => 'required',
        ];
    }
}
