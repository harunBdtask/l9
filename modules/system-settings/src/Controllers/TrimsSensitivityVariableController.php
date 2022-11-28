<?php

namespace SkylarkSoft\GoRMG\SystemSettings\Controllers;

use App\Http\Controllers\Controller;
use Exception;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use SkylarkSoft\GoRMG\SystemSettings\Models\Factory;
use SkylarkSoft\GoRMG\SystemSettings\Models\TrimsSensitivityVariable;

class TrimsSensitivityVariableController extends Controller
{
    public function index()
    {
        $factories = Factory::query()->pluck('factory_name', 'id');
        $variables = TrimsSensitivityVariable::VARIABLES;
        $trimsVariables = TrimsSensitivityVariable::query()->orderByDesc('id')->paginate();

        return view('system-settings::trims_sensitivity_variables.index', [
            "factories" => $factories,
            "variables" => $variables,
            "trimsVariables" => $trimsVariables,
        ]);
    }

    public function store(Request $request, TrimsSensitivityVariable $trimsSensitivityVariable): RedirectResponse
    {
        try {
            $trimsSensitivityVariable->fill($request->all())->save();
            Session::flash('alert-success', 'Data Saved Successfully!');
        } catch (Exception $e) {
            Session::flash('alert-danger', "Something went wrong!{$e->getMessage()}");
        } finally {
            return back();
        }
    }

    public function edit(TrimsSensitivityVariable $trimsSensitivityVariable): TrimsSensitivityVariable
    {
        return $trimsSensitivityVariable;
    }

    public function update(Request $request, TrimsSensitivityVariable $trimsSensitivityVariable): RedirectResponse
    {
        try {
            $trimsSensitivityVariable->fill($request->all())->save();
            Session::flash('alert-success', 'Data updated Successfully!');
        } catch (Exception $e) {
            Session::flash('alert-danger', "Something went wrong!{$e->getMessage()}");
        } finally {
            return back();
        }
    }

    public function destroy(TrimsSensitivityVariable $trimsSensitivityVariable): RedirectResponse
    {
        try {
            $trimsSensitivityVariable->delete();
            Session::flash('alert-success', 'Data deleted Successfully!');
        } catch (Exception $e) {
            Session::flash('alert-danger', "Something went wrong!{$e->getMessage()}");
        } finally {
            return back();
        }
    }
}
