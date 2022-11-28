<?php

namespace SkylarkSoft\GoRMG\SystemSettings\Controllers\Inventory;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use SkylarkSoft\GoRMG\SystemSettings\Models\DyesChemicalStoreVariableSetting;
use Symfony\Component\HttpFoundation\Response;

class DyesChemicalStoreVariableSettingsController extends Controller
{
    public function index()
    {
        $dyesChemicalStoreVariableSetting = DyesChemicalStoreVariableSetting::query()
            ->where('factory_id', factoryId())
            ->first();

        return view('system-settings::inventory.dyes_chemical_store.dyes_chemical_store_variable_settings', [
            'dyesChemicalStoreVariableSetting' => $dyesChemicalStoreVariableSetting,
        ]);
    }

    public function store(Request $request, DyesChemicalStoreVariableSetting $dyesChemicalStoreVariableSetting): RedirectResponse
    {
        try {
            $dyesChemicalStoreVariableSetting->fill($request->all())->save();
            Session::flash('success', 'Data Saved Successfully!');
        } catch (\Exception $exception) {
            Session::flash('error', "Something went wrong! {$exception->getMessage()}");
        }

        return back();
    }

    public function update(Request $request, DyesChemicalStoreVariableSetting $dyesChemicalStoreVariableSetting): RedirectResponse
    {
        try {
            $dyesChemicalStoreVariableSetting->fill($request->all())->save();
            Session::flash('success', 'Data Updated Successfully!');
        } catch (\Exception $exception) {
            Session::flash('error', "Something went wrong! {$exception->getMessage()}");
        }

        return back();
    }

    public function getApprovalMaintainStatus(): JsonResponse
    {
        try {
            $approvalMaintainStatus = DyesChemicalStoreVariableSetting::query()
                ->where('factory_id', factoryId())
                ->first()->approval_maintain ?? null;
            return response()->json($approvalMaintainStatus, Response::HTTP_OK);
        } catch (\Exception $exception) {
            return response()->json($exception->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
