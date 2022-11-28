<?php

namespace SkylarkSoft\GoRMG\SystemSettings\Controllers\Accounting;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Session;
use Symfony\Component\HttpFoundation\Response;
use SkylarkSoft\GoRMG\SystemSettings\Models\User;
use SkylarkSoft\GoRMG\SystemSettings\Models\BfVariableSetting;

class AccountingVariableSettingsController extends Controller
{
    public function index()
    {
        $variable = BfVariableSetting::first();
        $users = User::all();

        return view('system-settings::accounting.bf_variable_settings', compact('variable', 'users'));
    }

    public function store(Request $request)
    {
        $variable = BfVariableSetting::query()->first();

        try {
            $formData = $request->all();
            $formData['accounting_users'] = $request->get('accounting_users')??null;
            BfVariableSetting::updateOrCreate(
                ['id' => $variable->id ?? 1],
                $formData
            );
            Session::flash('success', 'Data Saved Successfully!');
        } catch (\Exception $exception) {
            Session::flash('error', "Something went wrong! {$exception->getMessage()}");
        }

        return back();
    }

}
