<?php


namespace SkylarkSoft\GoRMG\SystemSettings\Requests;

use Illuminate\Foundation\Http\FormRequest;
use SkylarkSoft\GoRMG\SystemSettings\Rules\UniqueSeasonRule;

class SeasonApiRequest extends FormRequest
{
    public function authorize() : bool
    {
        return true;
    }

    public function messages() : array
    {
        return [
            'required' => 'This field is required',
            'buyer_id.required' => 'Buyer is required',
        ];
    }

    public function rules() : array
    {
        return [
            'season_name' => ['required', new UniqueSeasonRule],
            'factory_id' => 'required',
            'buyer_id' => 'required',
            'year_from' => 'required',
            'year_to' => 'required',
        ];
    }
}
