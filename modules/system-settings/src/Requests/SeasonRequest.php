<?php


namespace SkylarkSoft\GoRMG\SystemSettings\Requests;

use Illuminate\Foundation\Http\FormRequest;
use SkylarkSoft\GoRMG\SystemSettings\Rules\UniqueSeasonArrayRule;
use SkylarkSoft\GoRMG\SystemSettings\Rules\UniqueSeasonsRule;

class SeasonRequest extends FormRequest
{
    public function authorize() : bool
    {
        return true;
    }

    public function messages() : array
    {
        return [
            'required' => 'Required Field',
            'array' => 'Required Field',
        ];
    }

    public function rules() : array
    {
        return [
            'factory_id' => 'required',
            'buyer_id' => 'required',
            'year_from' => 'required|array',
            'year_to' => 'required|array',
            'year_from.*' => ['required', new UniqueSeasonArrayRule],
            'year_to.*' => ['required', new UniqueSeasonArrayRule],
            'season_name' => ['required', 'array'],
            'season_name.*' => ['required', new UniqueSeasonsRule, new UniqueSeasonArrayRule],
        ];
    }
}
