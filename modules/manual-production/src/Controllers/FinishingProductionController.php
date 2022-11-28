<?php

namespace SkylarkSoft\GoRMG\ManualProduction\Controllers;

use App\Http\Controllers\Controller;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use SkylarkSoft\GoRMG\ManualProduction\Controllers\Search\SearchController;
use SkylarkSoft\GoRMG\ManualProduction\Models\ManualIronProduction;
use SkylarkSoft\GoRMG\ManualProduction\Models\ManualPolyPackingProduction;
use SkylarkSoft\GoRMG\ManualProduction\Requests\ManualIronProductionRequest;
use SkylarkSoft\GoRMG\ManualProduction\Requests\ManualPolyPackingProductionRequest;

class FinishingProductionController extends Controller
{
    public function ironProductionEntry()
    {
        return view("manual-production::finishing-production.iron");
    }

    public function polyPackingProductionEntry()
    {
        return view("manual-production::finishing-production.packaging");
    }

    public function ironProductionStore(ManualIronProductionRequest $request): JsonResponse
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
            $color_id = $request->color_id ?? null;
            $size_id = $request->size_id ?? null;
            $finishing_floor_id = $request->finishing_floor_id ?? null;
            $finishing_table_id = $request->finishing_table_id ?? null;
            $sub_finishing_floor_id = $request->sub_finishing_floor_id ?? null;
            $sub_finishing_table_id = $request->sub_finishing_table_id ?? null;
            $production_qty = $request->production_qty ?? 0;
            $re_iron_qty = $request->re_iron_qty ?? 0;
            $rejection_qty = $request->rejection_qty ?? 0;
            $produced_by = $request->produced_by ?? 1;
            $reporting_hour = $request->reporting_hour ?? 1;
            $challan_no = $request->challan_no ?? null;
            $supervisor = $request->supervisor ?? null;
            $remarks = $request->remarks ?? null;

            if ($size_id && is_array($size_id) && count($size_id) && $production_qty && is_array($production_qty)) {
                // for size wise data
                foreach ($size_id as $size_key => $s_id) {
                    if (!array_key_exists($size_key, $production_qty) || !$production_qty[$size_key] || $production_qty[$size_key] <= 0) {
                        continue;
                    }
                    $individual_production_qty = $production_qty[$size_key];
                    $individual_re_iron_qty = array_key_exists($size_key, $re_iron_qty) ? $re_iron_qty[$size_key] : 0;
                    $individual_rejection_qty = array_key_exists($size_key, $rejection_qty) ? $rejection_qty[$size_key] : 0;
                    $individual_challan_no = array_key_exists($size_key, $challan_no) ? $challan_no[$size_key] : null;
                    $manual_iron_production = new ManualIronProduction([
                        'production_date' => $production_date,
                        'source' => $source,
                        'factory_id' => $factory_id,
                        'subcontract_factory_id' => $subcontract_factory_id,
                        'buyer_id' => $buyer_id,
                        'order_id' => $order_id,
                        'garments_item_id' => $garments_item_id,
                        'purchase_order_id' => $purchase_order_id,
                        'color_id' => $color_id,
                        'size_id' => $s_id,
                        'finishing_floor_id' => $finishing_floor_id,
                        'finishing_table_id' => $finishing_table_id,
                        'sub_finishing_floor_id' => $sub_finishing_floor_id,
                        'sub_finishing_table_id' => $sub_finishing_table_id,
                        'production_qty' => $individual_production_qty,
                        're_iron_qty' => isset($individual_re_iron_qty) ? $individual_re_iron_qty : 0,
                        'rejection_qty' => isset($individual_rejection_qty) ? $individual_rejection_qty : 0,
                        'challan_no' => $individual_challan_no,
                        'produced_by' => $produced_by,
                        'reporting_hour' => $reporting_hour,
                        'supervisor' => $supervisor,
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
                    $individual_re_iron_qty = array_key_exists($color_key, $re_iron_qty) ? $re_iron_qty[$color_key] : 0;
                    $individual_rejection_qty = array_key_exists($color_key, $rejection_qty) ? $rejection_qty[$color_key] : 0;
                    $individual_challan_no = array_key_exists($color_key, $challan_no) ? $challan_no[$color_key] : null;
                    $manual_iron_production = new ManualIronProduction([
                        'production_date' => $production_date,
                        'source' => $source,
                        'factory_id' => $factory_id,
                        'subcontract_factory_id' => $subcontract_factory_id,
                        'buyer_id' => $buyer_id,
                        'order_id' => $order_id,
                        'garments_item_id' => $garments_item_id,
                        'purchase_order_id' => $purchase_order_id,
                        'color_id' => $c_id,
                        'size_id' => $size_id,
                        'finishing_floor_id' => $finishing_floor_id,
                        'finishing_table_id' => $finishing_table_id,
                        'sub_finishing_floor_id' => $sub_finishing_floor_id,
                        'sub_finishing_table_id' => $sub_finishing_table_id,
                        'production_qty' => $individual_production_qty,
                        're_iron_qty' => isset($individual_re_iron_qty) ? $individual_re_iron_qty : 0,
                        'rejection_qty' => isset($individual_rejection_qty) ? $individual_rejection_qty : 0,
                        'challan_no' => $individual_challan_no,
                        'produced_by' => $produced_by,
                        'reporting_hour' => $reporting_hour,
                        'supervisor' => $supervisor,
                        'remarks' => $remarks,
                    ]);
                    $manual_iron_production->save();
                }
            } elseif (!$color_id && !$size_id && $production_qty && !is_array($production_qty)) {
                // for order wise data
                $manual_iron_production = new ManualIronProduction([
                    'production_date' => $production_date,
                        'source' => $source,
                        'factory_id' => $factory_id,
                        'subcontract_factory_id' => $subcontract_factory_id,
                        'buyer_id' => $buyer_id,
                        'order_id' => $order_id,
                        'garments_item_id' => $garments_item_id,
                        'purchase_order_id' => $purchase_order_id,
                        'color_id' => $color_id,
                        'size_id' => $size_id,
                        'finishing_floor_id' => $finishing_floor_id,
                        'finishing_table_id' => $finishing_table_id,
                        'sub_finishing_floor_id' => $sub_finishing_floor_id,
                        'sub_finishing_table_id' => $sub_finishing_table_id,
                        'production_qty' => $production_qty,
                        're_iron_qty' => isset($re_iron_qty) ? $re_iron_qty : 0,
                        'rejection_qty' => isset($rejection_qty) ? $rejection_qty : 0,
                        'challan_no' => $challan_no,
                        'produced_by' => $produced_by,
                        'reporting_hour' => $reporting_hour,
                        'supervisor' => $supervisor,
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

    public function polyPackingProductionStore(ManualPolyPackingProductionRequest $request): JsonResponse
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
            $color_id = $request->color_id ?? null;
            $size_id = $request->size_id ?? null;
            $finishing_floor_id = $request->finishing_floor_id ?? null;
            $finishing_table_id = $request->finishing_table_id ?? null;
            $sub_finishing_floor_id = $request->sub_finishing_floor_id ?? null;
            $sub_finishing_table_id = $request->sub_finishing_table_id ?? null;
            $production_qty = $request->production_qty ?? 0;
            $alter_qty = $request->alter_qty ?? 0;
            $carton_qty = $request->carton_qty ?? 0;
            $rejection_qty = $request->rejection_qty ?? 0;
            $produced_by = $request->produced_by ?? 1;
            $reporting_hour = $request->reporting_hour ?? 1;
            $challan_no = $request->challan_no ?? null;
            $supervisor = $request->supervisor ?? null;
            $remarks = $request->remarks ?? null;

            if ($size_id && is_array($size_id) && count($size_id) && $production_qty && is_array($production_qty)) {
                // for size wise data
                foreach ($size_id as $size_key => $s_id) {
                    if (!array_key_exists($size_key, $production_qty) || !$production_qty[$size_key] || $production_qty[$size_key] <= 0) {
                        continue;
                    }
                    $individual_production_qty = $production_qty[$size_key];
                    $individual_alter_qty = array_key_exists($size_key, $alter_qty) ? $alter_qty[$size_key] : 0;
                    $individual_carton_qty = array_key_exists($size_key, $carton_qty) ? $carton_qty[$size_key] : 0;
                    $individual_rejection_qty = array_key_exists($size_key, $rejection_qty) ? $rejection_qty[$size_key] : 0;
                    $individual_challan_no = array_key_exists($size_key, $challan_no) ? $challan_no[$size_key] : null;
                    $manual_iron_production = new ManualPolyPackingProduction([
                        'production_date' => $production_date,
                        'source' => $source,
                        'factory_id' => $factory_id,
                        'subcontract_factory_id' => $subcontract_factory_id,
                        'buyer_id' => $buyer_id,
                        'order_id' => $order_id,
                        'garments_item_id' => $garments_item_id,
                        'purchase_order_id' => $purchase_order_id,
                        'color_id' => $color_id,
                        'size_id' => $s_id,
                        'finishing_floor_id' => $finishing_floor_id,
                        'finishing_table_id' => $finishing_table_id,
                        'sub_finishing_floor_id' => $sub_finishing_floor_id,
                        'sub_finishing_table_id' => $sub_finishing_table_id,
                        'production_qty' => $individual_production_qty,
                        'alter_qty' => isset($individual_alter_qty) ? $individual_alter_qty : 0,
                        'carton_qty' => isset($individual_carton_qty) ? $individual_carton_qty : 0,
                        'rejection_qty' => isset($individual_rejection_qty) ? $individual_rejection_qty : 0,
                        'challan_no' => $individual_challan_no,
                        'produced_by' => $produced_by,
                        'reporting_hour' => $reporting_hour,
                        'supervisor' => $supervisor,
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
                    $individual_alter_qty = array_key_exists($color_key, $alter_qty) ? $alter_qty[$color_key] : 0;
                    $individual_carton_qty = array_key_exists($color_key, $carton_qty) ? $carton_qty[$color_key] : 0;
                    $individual_rejection_qty = array_key_exists($color_key, $rejection_qty) ? $rejection_qty[$color_key] : 0;
                    $individual_challan_no = array_key_exists($color_key, $challan_no) ? $challan_no[$color_key] : null;
                    $manual_iron_production = new ManualPolyPackingProduction([
                        'production_date' => $production_date,
                        'source' => $source,
                        'factory_id' => $factory_id,
                        'subcontract_factory_id' => $subcontract_factory_id,
                        'buyer_id' => $buyer_id,
                        'order_id' => $order_id,
                        'garments_item_id' => $garments_item_id,
                        'purchase_order_id' => $purchase_order_id,
                        'color_id' => $c_id,
                        'size_id' => $size_id,
                        'finishing_floor_id' => $finishing_floor_id,
                        'finishing_table_id' => $finishing_table_id,
                        'sub_finishing_floor_id' => $sub_finishing_floor_id,
                        'sub_finishing_table_id' => $sub_finishing_table_id,
                        'production_qty' => $individual_production_qty,
                        'alter_qty' => isset($individual_alter_qty) ? $individual_alter_qty : 0,
                        'carton_qty' => isset($individual_carton_qty) ? $individual_carton_qty : 0,
                        'rejection_qty' => isset($individual_rejection_qty) ? $individual_rejection_qty : 0,
                        'challan_no' => $individual_challan_no,
                        'produced_by' => $produced_by,
                        'reporting_hour' => $reporting_hour,
                        'supervisor' => $supervisor,
                        'remarks' => $remarks,
                    ]);
                    $manual_iron_production->save();
                }
            } elseif (!$color_id && !$size_id && $production_qty && !is_array($production_qty)) {
                // for order wise data
                $manual_iron_production = new ManualPolyPackingProduction([
                    'production_date' => $production_date,
                        'source' => $source,
                        'factory_id' => $factory_id,
                        'subcontract_factory_id' => $subcontract_factory_id,
                        'buyer_id' => $buyer_id,
                        'order_id' => $order_id,
                        'garments_item_id' => $garments_item_id,
                        'purchase_order_id' => $purchase_order_id,
                        'color_id' => $color_id,
                        'size_id' => $size_id,
                        'finishing_floor_id' => $finishing_floor_id,
                        'finishing_table_id' => $finishing_table_id,
                        'sub_finishing_floor_id' => $sub_finishing_floor_id,
                        'sub_finishing_table_id' => $sub_finishing_table_id,
                        'production_qty' => $production_qty,
                        'alter_qty' => isset($alter_qty) ? $alter_qty : 0,
                        'carton_qty' => isset($carton_qty) ? $carton_qty : 0,
                        'rejection_qty' => isset($rejection_qty) ? $rejection_qty : 0,
                        'challan_no' => $challan_no,
                        'produced_by' => $produced_by,
                        'reporting_hour' => $reporting_hour,
                        'supervisor' => $supervisor,
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
