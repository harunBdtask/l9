<?php

namespace SkylarkSoft\GoRMG\ManualProduction\Controllers;

use App\Http\Controllers\Controller;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use SkylarkSoft\GoRMG\ManualProduction\Controllers\Search\SearchController;
use SkylarkSoft\GoRMG\ManualProduction\Models\ManualInspectionProduction;
use SkylarkSoft\GoRMG\ManualProduction\Requests\ManualInspectionProductionRequest;

class InspectionController extends Controller
{
    public function inspectionEntry()
    {
        return view("manual-production::inspection.index");
    }

    public function store(ManualInspectionProductionRequest $request)
    {
        try {
            DB::beginTransaction();
            $production_date = $request->production_date ?? null;
            $source = $request->source ?? null;
            $factory_id = $request->factory_id ?? null;
            $subcontract_factory_id = $request->subcontract_factory_id ?? null;
            $buyer_id = $request->buyer_id ?? null;
            $order_id = $request->order_id ?? null;
            $garments_item_id = $request->garments_item_id ?? null;
            $purchase_order_id = $request->purchase_order_id ?? null;
            $production_qty = $request->production_qty ?? 0;
            $reason = $request->reason ?? null;
            $responsible_person = $request->responsible_person ?? null;
            $status = $request->status ?? 1;
            $remarks = $request->remarks ?? null;

            $manual_iron_production = new ManualInspectionProduction([
                'production_date' => $production_date,
                'source' => $source,
                'factory_id' => $factory_id,
                'subcontract_factory_id' => $subcontract_factory_id,
                'buyer_id' => $buyer_id,
                'order_id' => $order_id,
                'garments_item_id' => $garments_item_id,
                'purchase_order_id' => $purchase_order_id,
                'production_qty' => $production_qty,
                'reason' => $reason,
                'responsible_person' => $responsible_person,
                'remarks' => $remarks,
                'status' => $status,
            ]);
            $manual_iron_production->save();

            $data = [];
            if ($purchase_order_id && $garments_item_id) {
                $data = SearchController::getSingleSearchData($purchase_order_id, $garments_item_id);
            }
            DB::commit();
            return response()->json([
                'error' => null,
                'message' => 'Data stored successfully!',
                'status' => 200,
                'data' => $data
            ]);
        } catch (Exception $e) {
            DB::rollBack();
            return response()->json([
                'error' => $e->getMessage(),
                'message' => 'Something went wrong!',
                'status' => 500,
            ]);
        }
    }
}
