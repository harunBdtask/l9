<?php

namespace SkylarkSoft\GoRMG\SystemSettings\Controllers\Inventory;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Session;
use SkylarkSoft\GoRMG\SystemSettings\Models\FabricStoreVariableSetting;

class FabricStoreVariableSettingsController extends Controller
{
    public function index()
    {
        $fabricStoreVariableSetting = FabricStoreVariableSetting::query()->factoryFilter()->first();

        return view('system-settings::inventory.fabric_store.fabric_store_variable_settings', [
            'fabricStoreVariableSetting' => $fabricStoreVariableSetting,
        ]);
    }

    public function store(Request $request, FabricStoreVariableSetting $fabricStoreVariableSetting): RedirectResponse
    {
        try {
            $fabricStoreVariableSetting->fill($request->all())->save();
            Session::flash('success', 'Data Saved Successfully!');
        } catch (\Exception $exception) {
            Session::flash('error', "Something went wrong! {$exception->getMessage()}");
        }

        return back();
    }

    public function update(Request $request, FabricStoreVariableSetting $fabricStoreVariableSetting): RedirectResponse
    {
        try {
            $fabricStoreVariableSetting->fill($request->all())->save();
            Session::flash('success', 'Data Updated Successfully!');
        } catch (\Exception $exception) {
            Session::flash('error', "Something went wrong! {$exception->getMessage()}");
        }

        return back();
    }
}
