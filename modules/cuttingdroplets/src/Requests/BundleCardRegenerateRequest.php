<?php

namespace SkylarkSoft\GoRMG\Cuttingdroplets\Requests;

use Illuminate\Foundation\Http\FormRequest;
use SkylarkSoft\GoRMG\Cuttingdroplets\Rules\UniqueBundleGenerationPartAndTypeWise;

class BundleCardRegenerateRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return \Auth::check();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'part_id' => 'required',
            'type_id' => ['required', new UniqueBundleGenerationPartAndTypeWise]
        ];
    }
}
