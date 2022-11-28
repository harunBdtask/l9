<?php

namespace SkylarkSoft\GoRMG\ManualProduction\Controllers;

use App\Http\Controllers\Controller;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use SkylarkSoft\GoRMG\ManualProduction\Controllers\Search\SearchController;
use SkylarkSoft\GoRMG\ManualProduction\Models\ManualShipmentProduction;
use SkylarkSoft\GoRMG\ManualProduction\Requests\ManualShipmentProductionRequest;

class ShipmentController extends Controller
{
    public function shipmentEntry()
    {
        return view("manual-production::shipment-ex-factory.index");
    }

    public function store(ManualShipmentProductionRequest $request)
    {
        try {
            DB::beginTransaction();
            $production_date = $request->production_date ?? null;
            $factory_id = $request->factory_id ?? null;
            $buyer_id = $request->buyer_id ?? null;
            $order_id = $request->order_id ?? null;
            $garments_item_id = $request->garments_item_id ?? null;
            $purchase_order_id = $request->purchase_order_id ?? null;
            $color_id = $request->color_id ?? null;
            $size_id = $request->size_id ?? null;
            $production_qty = $request->production_qty ?? 0;
            $short_qty = $request->short_qty ?? 0;
            $carton_qty = $request->carton_qty ?? 0;
            $status = $request->status ?? 1;
            $responsible_person = $request->responsible_person ?? null;
            $agent = $request->agent ?? null;
            $destination = $request->destination ?? null;
            $vehicle_no = $request->vehicle_no ?? null;
            $driver = $request->driver ?? null;
            $remarks = $request->remarks ?? null;

            if ($size_id && is_array($size_id) && count($size_id) && $production_qty && is_array($production_qty)) {
                // for size wise data
                foreach ($size_id as $size_key => $s_id) {
                    if (!array_key_exists($size_key, $production_qty) || !$production_qty[$size_key] || $production_qty[$size_key] <= 0) {
                        continue;
                    }
                    $individual_production_qty = $production_qty[$size_key];
                    $individual_short_qty = array_key_exists($size_key, $short_qty) ? $short_qty[$size_key] : 0;
                    $individual_carton_qty = array_key_exists($size_key, $carton_qty) ? $carton_qty[$size_key] : 0;
                    $individual_status = array_key_exists($size_key, $status) ? $status[$size_key] : 1;
                    $manual_iron_production = new ManualShipmentProduction([
                        'production_date' => $production_date,
                        'factory_id' => $factory_id,
                        'buyer_id' => $buyer_id,
                        'order_id' => $order_id,
                        'garments_item_id' => $garments_item_id,
                        'purchase_order_id' => $purchase_order_id,
                        'color_id' => $color_id,
                        'size_id' => $s_id,
                        'production_qty' => $individual_production_qty,
                        'short_qty' => isset($individual_short_qty) ? $individual_short_qty : 0,
                        'carton_qty' => isset($individual_carton_qty) ? $individual_carton_qty : 0,
                        'status' => isset($individual_status) ? $individual_status : 1,
                        'responsible_person' => $responsible_person,
                        'agent' => $agent,
                        'destination' => $destination,
                        'vehicle_no' => $vehicle_no,
                        'driver' => $driver,
                        'remarks' => $remarks,
                    ]);
                    $manual_iron_production->save();
                }
            } elseif ($color_id && is_array($color_id) && count($color_id) && $production_qty && is_array($production_qty)) {
                // for color wise data rule
                foreach ($color_id as $color_key => $c_id) {
                    if (!array_key_exists($color_key, $production_qty) || !$production_qty[$color_key] || $production_qty[$color_key] <= 0) {
                        continue;
                    }
                    $individual_production_qty = $production_qty[$color_key];
                    $individual_short_qty = array_key_exists($color_key, $short_qty) ? $short_qty[$color_key] : 0;
                    $individual_carton_qty = array_key_exists($color_key, $carton_qty) ? $carton_qty[$color_key] : 0;
                    $individual_status = array_key_exists($color_key, $status) ? $status[$color_key] : 0;
                    $manual_iron_production = new ManualShipmentProduction([
                        'production_date' => $production_date,
                        'factory_id' => $factory_id,
                        'buyer_id' => $buyer_id,
                        'order_id' => $order_id,
                        'garments_item_id' => $garments_item_id,
                        'purchase_order_id' => $purchase_order_id,
                        'color_id' => $c_id,
                        'size_id' => $size_id,
                        'production_qty' => $individual_production_qty,
                        'short_qty' => isset($individual_short_qty) ? $individual_short_qty : 0,
                        'carton_qty' => isset($individual_carton_qty) ? $individual_carton_qty : 0,
                        'status' => isset($individual_status) ? $individual_status : 1,
                        'responsible_person' => $responsible_person,
                        'agent' => $agent,
                        'destination' => $destination,
                        'vehicle_no' => $vehicle_no,
                        'driver' => $driver,
                        'remarks' => $remarks,
                    ]);
                    $manual_iron_production->save();
                }
            } elseif (!$color_id && !$size_id && $production_qty && !is_array($production_qty)) {
                // for order wise data
                $manual_iron_production = new ManualShipmentProduction([
                    'production_date' => $production_date,
                    'factory_id' => $factory_id,
                    'buyer_id' => $buyer_id,
                    'order_id' => $order_id,
                    'garments_item_id' => $garments_item_id,
                    'purchase_order_id' => $purchase_order_id,
                    'color_id' => $color_id,
                    'size_id' => $size_id,
                    'production_qty' => $production_qty,
                    'short_qty' => isset($short_qty) ? $short_qty : 0,
                    'carton_qty' => isset($carton_qty) ? $carton_qty : 0,
                    'status' => isset($status) ? $status : 1,
                    'responsible_person' => $responsible_person,
                    'agent' => $agent,
                    'destination' => $destination,
                    'vehicle_no' => $vehicle_no,
                    'driver' => $driver,
                    'remarks' => $remarks,
                ]);
                $manual_iron_production->save();
            } else {
                // for indistinctive data do not save any data
            }
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
