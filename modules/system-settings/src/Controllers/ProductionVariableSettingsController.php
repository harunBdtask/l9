<?php


namespace SkylarkSoft\GoRMG\SystemSettings\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Session;
use SkylarkSoft\GoRMG\SystemSettings\Models\Factory;
use SkylarkSoft\GoRMG\SystemSettings\Models\MerchandisingVariableSettings;
use SkylarkSoft\GoRMG\SystemSettings\Models\ProductionVariableSettings;

class ProductionVariableSettingsController
{
    public function index()
    {
        $factory_all = Factory::query()->get();

        if (getRole() == 'user') {
            $factories[factoryId()] = factoryName();
        }
        else {
            $factories = ['' => 'Select'];
            foreach ($factory_all as $key => $value) {
                $factories[$value->id] = $value->factory_name;
            }
        }

        $productionVariable = $this->getProductionVariableSetting(factoryId(), false);

        return view("system-settings::production_variable_settings.knitting_production_variable", compact('factories', 'productionVariable'));
    }

    public function store(Request $request, $id = null)
    {
        try {
            $factoryId = $request->get('factory_id');
            $productionVariable = ProductionVariableSettings::query()->findOrNew($id);

            $variableDetails = [
                'knitting_process_maintain' => $request->input('knitting_process_maintain'),
                'fabric_production_maintain' => $request->input('fabric_production_maintain'),
                'yarn_allocation_maintain' => $request->input('yarn_allocation_maintain'),
                'shift_wise_production_maintain' => $request->input('shift_wise_production_maintain'),
                'pcs_production_maintain' => $request->input('pcs_production_maintain'),
                'machine_wise_production_maintain' => $request->input('machine_wise_production_maintain'),
                'knitting_qc_maintain' => $request->input('knitting_qc_maintain'),
            ];

            $requestedData = [
                'factory_id' => $factoryId,
                'variables_name' => json_encode(array_keys($variableDetails)),
                'variables_details' => json_encode($variableDetails),
            ];

            $productionVariable->fill($requestedData)->save();
            Session::flash('alert-success', 'Data Stored Successfully!!');
            return back();
        } catch (\Exception $exception) {
            return response()->json($exception->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function getProductionVariableSetting($factoryId = null, $isJson = true) {
        $factoryId = $factoryId ?? factoryId();
        $productionVariable = ProductionVariableSettings::query()->where('factory_id', $factoryId)->first();
        if ($productionVariable) {
            $variableDetails = json_decode($productionVariable['variables_details'], true);
            $productionVariable = array_merge($variableDetails, $productionVariable->toArray());
        }

        if ($isJson) {
            return response()->json($productionVariable, 200);
        }
        return $productionVariable;
    }
}
