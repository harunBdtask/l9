<?php


namespace SkylarkSoft\GoRMG\SystemSettings\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class FinancialParameterSetupRequest extends FormRequest
{
    public function authorize() : bool
    {
        return Auth::check();
    }

    public function rules() : array
    {
        return [
            'factory_id' => 'required',
            'applying_period' => 'required',
//            'asking_profit' => 'required',
//            'factory_machine' => 'required',
//            'monthly_cm_expense' => 'required',
//            'working_hour' => 'required',
            'cost_per_minute' => 'required',
//            'asking_avg_rate' => 'required'
        ];
    }
}
