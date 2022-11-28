<?php

namespace SkylarkSoft\GoRMG\Merchandising\Controllers;

use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use SkylarkSoft\GoRMG\Merchandising\Models\CostingDetails;
use SkylarkSoft\GoRMG\Merchandising\Models\FabricCostDetails;
use SkylarkSoft\GoRMG\Merchandising\Services\FabricDescriptionService;
use SkylarkSoft\GoRMG\SystemSettings\Services\ConsumptionBasisService;
use SkylarkSoft\GoRMG\SystemSettings\Services\DiaTypesService;
use SkylarkSoft\GoRMG\SystemSettings\Services\FabricSourceService;

class FabricCostDetailsController extends Controller
{
    public function store(Request $request)
    {
        try {
            DB::beginTransaction();
            $fabric_cost = new FabricCostDetails();
            $requested_data = $request->all();
            $fabric_consumption_details['details'] = $request->fabricConsumptionForm;
            $fabric_consumption_details['calculation'] = $request->fabricConsumptionCalculation;
            $requested_data = Arr::set($requested_data, "fabric_consumption_details", json_encode($fabric_consumption_details));
            $fabric_cost->fill($requested_data)->save();
            DB::commit();
            $response = [
                "status" => 200,
                "message" => "Fabric Cost added successfully",
                "type" => "success",
            ];

            return response()->json($response, Response::HTTP_OK);
        } catch (\Exception $exception) {
            DB::rollBack();

            return response()->json($exception->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function update(Request $request, $id): \Illuminate\Http\JsonResponse
    {
        try {
            DB::beginTransaction();
            $fabric_cost = FabricCostDetails::where("id", $id)->first();
            $requested_data = $request->all();
            $fabric_consumption_details['details'] = $request->fabricConsumptionForm;
            $fabric_consumption_details['calculation'] = $request->fabricConsumptionCalculation;
            $requested_data = Arr::set($requested_data, "fabric_consumption_details", json_encode($fabric_consumption_details));
            $fabric_cost->fill($requested_data)->save();
            DB::commit();
            $response = [
                "status" => 200,
                "message" => "Fabric Cost added successfully",
                "type" => "success",
            ];

            return response()->json($response, Response::HTTP_OK);
        } catch (\Exception $exception) {
            DB::rollBack();

            return response()->json($exception->getMessage(), $exception->getCode());
        }
    }

    public function quotationFabricCost($quotation): \Illuminate\Http\JsonResponse
    {
        try {
            $fabric_cost = FabricCostDetails::where("quotation_id", $quotation)
                ->with(['item', 'bodyPart', 'fabricNature', 'colorType', 'supplier'])
                ->get()->map(function ($fabric) {
                    return $this->format($fabric);
                });
            //$fabric_cost = CostingDetails::where("")
            return response()->json($fabric_cost, Response::HTTP_OK);
        } catch (\Exception $exception) {
            echo $exception->getMessage();

            return response()->json($exception->getMessage(), $exception->getCode());
        }
    }

    /**
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function deleteFabricCost($id): \Illuminate\Http\JsonResponse
    {
        try {
            FabricCostDetails::where("id", $id)->first()->delete();
            $response = [
                "message" => "Fabric Cost Deleted Successfully",
            ];

            return response()->json($response, Response::HTTP_OK);
        } catch (\Exception $exception) {
            return response()->json($exception->getMessage(), $exception->getCode());
        }
    }

    /**
     * @param $fabric
     * @return array
     */
    private function format($fabric): array
    {
        return [
            "id" => $fabric->id,
            "item_name" => $fabric->item->name,
            "item_id" => $fabric->garment_item_id,
            "body_part" => $fabric->bodyPart->name,
            "body_part_id" => $fabric->body_part_id,
            "body_part_type" => $fabric->bodyPart->type,
            "fab_nature" => $fabric->fabricNature->name,
            "fabric_nature_id" => $fabric->fabric_nature_id,
            "color_type_id" => $fabric->color_type_id,
            "color_type" => $fabric->colorType->color_types,
            "fabric_composition_id" => $fabric->fabric_composition_id,
            "fabric_description" => FabricDescriptionService::description($fabric->fabric_composition_id),
            "fabric_source" => FabricSourceService::get($fabric->fabric_source)['name'],
            "fabric_source_id" => $fabric->fabric_source,
            "supplier" => $fabric->supplier->name,
            "supplier_id" => $fabric->supplier->id,
            "gsm" => $fabric->gsm,
            "dia_type_value" => DiaTypesService::get($fabric->dia_type)['name'],
            "dia_type" => $fabric->dia_type,
            "fabric_cons" => $fabric->fabric_cons,
            "rate" => $fabric->rate,
            "amount" => $fabric->amount,
            "consumption_basis" => ConsumptionBasisService::get($fabric->consumption_basis)['name'],
            "consumption_basis_id" => $fabric->consumption_basis,
            "status" => $fabric->status === 1 ? 'Active' : 'Inactive',
            "status_id" => $fabric->status,
            "uom" => $fabric->uom,
            "fabric_consumption_details" => json_decode($fabric->fabric_consumption_details),
        ];
    }

    public function save(Request $request)
    {
        $data['details']['conversionCostForm'] = $request->get('conversionCostForm');
        $data['details']['yarnCostForm'] = $request->get('yarnCostForm');
        $data['details']['fabricForm'] = $request->get('fabricForm');

        $data['calculation']['sumFabricCosting'] = $request->get('sumFabricCosting');
        $data['calculation']['sumConversionCosting'] = $request->get('sumConversionCosting');
        $data['calculation']['sumYarnCosting'] = $request->get('sumYarnCosting');
        $data['calculation']['total'] = $request->get('sum');
        $details = $data;
        $fab['details'] = $details;
        $fab['price_quotation_id'] = $request->get('price_quotation_id');
        $fab['type'] = 'fabric_costing';
        $cost_details = CostingDetails::where("price_quotation_id", $request->get('price_quotation_id'))
            ->where("type", 'fabric_costing')
            ->first();
        if (! $cost_details) {
            $type = "Created";
            CostingDetails::create($fab);
        } else {
            $type = "Updated";
            $cost_details->fill($fab)->save();
        }

        return response()->json([
            'message' => "Data {$type} Successfully",
            'data' => $fab,
        ]);
    }
}
