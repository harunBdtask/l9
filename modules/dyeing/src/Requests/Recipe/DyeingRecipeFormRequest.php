<?php

namespace SkylarkSoft\GoRMG\Dyeing\Requests\Recipe;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class DyeingRecipeFormRequest extends FormRequest
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
            'dyeing_batch_id' => 'required',
            'dyeing_batch_no' => 'required',
            'liquor_ratio' => 'required',
            'total_liq_level' => 'required',
            'shift_id' => 'required',
            'recipe_date' => 'required',
        ];
    }
}
