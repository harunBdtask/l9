<?php

namespace SkylarkSoft\GoRMG\Commercial\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use SkylarkSoft\GoRMG\Commercial\Models\CommercialVariable;
use SkylarkSoft\GoRMG\SystemSettings\Models\Factory;

class CommercialVariableController extends Controller
{
    public function create(Request $request)
    {
        $factories = Factory::pluck('factory_name', 'id')->prepend('Select Beneficiary', '');
        $variable = null;

        return view('commercial::variable.commercial-variables', compact('factories', 'variable'));
    }

    public function store(Request $request)
    {
        $this->validateRequest($request);

        try {
            $variable = CommercialVariable::firstOrNew(['id' => $request->id]);

            $variable->factory_id = $request->factory_id;
            $variable->variable_name = $request->variable_name;
            $variable->value = $request->value;

            $variable->save();
            Session::flash('alert-success', 'Successfully Saved!');
        } catch (\Exception $e) {
            Session::flash('alert-danger', 'Something Went Wrong!');
            $request->flash();
        }

        return redirect()->back();
    }

    private function validateRequest(Request $request)
    {
        $request->validate([
            'value' => 'required',
        ]);
    }

    public function generateVariableSettingsForm()
    {

//        return request('factory');
        $factory = request('factory');
        $value = request('value');
        $variable = CommercialVariable::where(['variable_name' => $value, 'factory_id' => $factory])->first();
        if (! ($variable)) {
            $variable = null;
        }
        $view = view('commercial::variable.partials.btb-limit-percent-form', compact('variable'))
            ->render();

        return response($view);
    }

    public function fetchBtbPercent()
    {
        $factoryId = request('factory');
        $value = request('value');
        $variable = CommercialVariable::where(['variable_name' => $value ,'factory_id' => $factoryId])->first();

        return response($variable);
    }
}
