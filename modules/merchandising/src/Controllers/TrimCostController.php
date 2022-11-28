<?php

namespace SkylarkSoft\GoRMG\Merchandising\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use SkylarkSoft\GoRMG\Merchandising\Models\CostingDetails;
use SkylarkSoft\GoRMG\SystemSettings\Models\CostingTemplate;

class TrimCostController extends Controller
{
    public function store(Request $request)
    {
        try {
            DB::beginTransaction();
            $cost_details = CostingDetails::where("price_quotation_id", $request->price_quotation_id)
                ->where("type", $request->type)
                ->first();
            $type = "Updated";
            if (! $cost_details) {
                $cost_details = new CostingDetails();
                $type = "Added";
            }
            $requested_data = $request->all();
            $cost_details_form['details'] = $request->details;
            $cost_details_form['calculation'] = $request->calcuation;
            $requested_data = Arr::set($requested_data, "details", $cost_details_form);
            $cost_details->fill($requested_data)->save();
            $message = $request->is_template ? "Trims cost template added successfully" : "Trims Cost {$type} successfully";
            if ($request->is_template) {
                $costing_template = new CostingTemplate();
                $costing_template->fill($requested_data)->save();
            }
            DB::commit();
            $response = [
                "status" => 200,
                "message" => $message,
                "type" => "success",
            ];

            return response()->json($response, Response::HTTP_OK);
        } catch (\Exception $exception) {
            DB::rollBack();

            return response()->json($exception->getMessage(), $exception->getCode());
        }
    }
}
