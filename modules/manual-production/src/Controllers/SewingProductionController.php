<?php

namespace SkylarkSoft\GoRMG\ManualProduction\Controllers;

use App\Http\Controllers\Controller;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use SkylarkSoft\GoRMG\ManualProduction\Controllers\Search\SearchController;
use SkylarkSoft\GoRMG\ManualProduction\Models\ManualCuttingProduction;
use SkylarkSoft\GoRMG\ManualProduction\Models\ManualHourlySewingProduction;
use SkylarkSoft\GoRMG\ManualProduction\Models\ManualSewingInputProduction;
use SkylarkSoft\GoRMG\ManualProduction\Requests\ManualSewingInputProductionRequest;
use SkylarkSoft\GoRMG\ManualProduction\Requests\ManualSewingOutputProductionRequest;

class SewingProductionController extends Controller
{
    public function inputEntry()
    {
        return view("manual-production::sewing.input-entry");
    }

    public function inputStore(ManualSewingInputProductionRequest $request): JsonResponse
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
            $floor_id = $request->floor_id ?? null;
            $line_id = $request->line_id ?? null;
            $sub_sewing_floor_id = $request->sub_sewing_floor_id ?? null;
            $sub_sewing_line_id = $request->sub_sewing_line_id ?? null;
            $production_qty = $request->production_qty ?? null;
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
                    $individual_challan_no = array_key_exists($size_key, $challan_no) ? $challan_no[$size_key] : 0;
                    $manual_input_production = new ManualSewingInputProduction([
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
                        'floor_id' => $floor_id,
                        'line_id' => $line_id,
                        'sub_sewing_floor_id' => $sub_sewing_floor_id,
                        'sub_sewing_line_id' => $sub_sewing_line_id,
                        'production_qty' => $individual_production_qty,
                        'challan_no' => $individual_challan_no,
                        'supervisor' => $supervisor,
                        'remarks' => $remarks,
                    ]);
                    $manual_input_production->save();
                }
            } elseif ($color_id && is_array($color_id) && count($color_id) && $production_qty && is_array($production_qty)) {
                // for color wise data rule
                foreach ($color_id as $color_key => $c_id) {
                    if (!array_key_exists($color_key, $production_qty) || !$production_qty[$color_key] || $production_qty[$color_key] <= 0) {
                        continue;
                    }
                    $individual_production_qty = $production_qty[$color_key];
                    $individual_challan_no = array_key_exists($color_key, $challan_no) ? $challan_no[$color_key] : 0;
                    $manual_input_production = new ManualSewingInputProduction([
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
                        'floor_id' => $floor_id,
                        'line_id' => $line_id,
                        'sub_sewing_floor_id' => $sub_sewing_floor_id,
                        'sub_sewing_line_id' => $sub_sewing_line_id,
                        'production_qty' => $individual_production_qty,
                        'challan_no' => $individual_challan_no,
                        'supervisor' => $supervisor,
                        'remarks' => $remarks,
                    ]);
                    $manual_input_production->save();
                }
            } elseif (!$color_id && !$size_id && $production_qty && !is_array($production_qty)) {
                // for order wise data
                $manual_input_production = new ManualSewingInputProduction([
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
                    'floor_id' => $floor_id,
                    'line_id' => $line_id,
                    'sub_sewing_floor_id' => $sub_sewing_floor_id,
                    'sub_sewing_line_id' => $sub_sewing_line_id,
                    'production_qty' => $production_qty,
                    'challan_no' => $challan_no,
                    'supervisor' => $supervisor,
                    'remarks' => $remarks,
                ]);
                $manual_input_production->save();
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

    public function outputEntry()
    {
        return view("manual-production::sewing.output-entry");
    }

    public function outputStore(ManualSewingOutputProductionRequest $request): JsonResponse
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
            $floor_id = $request->floor_id ?? null;
            $line_id = $request->line_id ?? null;
            $sub_sewing_floor_id = $request->sub_sewing_floor_id ?? null;
            $sub_sewing_line_id = $request->sub_sewing_line_id ?? null;
            $production_qty = $request->production_qty ?? null;
            $rejection_qty = $request->rejection_qty ?? null;
            $alter_qty = $request->alter_qty ?? null;
            $challan_no = $request->challan_no ?? null;
            $supervisor = $request->supervisor ?? null;
            $produced_by = $request->produced_by ?? null;
            $reporting_hour = $request->reporting_hour ?? null;
            $remarks = $request->remarks ?? null;
            $entry_format = $request->entry_format ?? null;
            $hour_8 = $request->hour_8 ?? 0;
            $hour_9 = $request->hour_9 ?? 0;
            $hour_10 = $request->hour_10 ?? 0;
            $hour_11 = $request->hour_11 ?? 0;
            $hour_12 = $request->hour_12 ?? 0;
            $hour_13 = $request->hour_13 ?? 0;
            $hour_14 = $request->hour_14 ?? 0;
            $hour_15 = $request->hour_15 ?? 0;
            $hour_16 = $request->hour_16 ?? 0;
            $hour_17 = $request->hour_17 ?? 0;
            $hour_18 = $request->hour_18 ?? 0;
            $hour_19 = $request->hour_19 ?? 0;
            $hour_20 = $request->hour_20 ?? 0;
            $hour_21 = $request->hour_21 ?? 0;
            $hour_22 = $request->hour_22 ?? 0;
            $hour_23 = $request->hour_23 ?? 0;

            if ($size_id && is_array($size_id) && count($size_id) && $production_qty && is_array($production_qty)) {
                // for size wise data
                foreach ($size_id as $size_key => $s_id) {
                    if (!array_key_exists($size_key, $production_qty) || !$production_qty[$size_key] || $production_qty[$size_key] <= 0) {
                        continue;
                    }

                    $individual_hour_8  = array_key_exists($size_key, $hour_8) ? $hour_8[$size_key] : 0;
                    $individual_hour_9  = array_key_exists($size_key, $hour_9) ? $hour_9[$size_key] : 0;
                    $individual_hour_10 = array_key_exists($size_key, $hour_10) ? $hour_10[$size_key] : 0;
                    $individual_hour_11 = array_key_exists($size_key, $hour_11) ? $hour_11[$size_key] : 0;
                    $individual_hour_12 = array_key_exists($size_key, $hour_12) ? $hour_12[$size_key] : 0;
                    $individual_hour_13 = array_key_exists($size_key, $hour_13) ? $hour_13[$size_key] : 0;
                    $individual_hour_14 = array_key_exists($size_key, $hour_14) ? $hour_14[$size_key] : 0;
                    $individual_hour_15 = array_key_exists($size_key, $hour_15) ? $hour_15[$size_key] : 0;
                    $individual_hour_16 = array_key_exists($size_key, $hour_16) ? $hour_16[$size_key] : 0;
                    $individual_hour_17 = array_key_exists($size_key, $hour_17) ? $hour_17[$size_key] : 0;
                    $individual_hour_18 = array_key_exists($size_key, $hour_18) ? $hour_18[$size_key] : 0;
                    $individual_hour_19 = array_key_exists($size_key, $hour_19) ? $hour_19[$size_key] : 0;
                    $individual_hour_20 = array_key_exists($size_key, $hour_20) ? $hour_20[$size_key] : 0;
                    $individual_hour_21 = array_key_exists($size_key, $hour_21) ? $hour_21[$size_key] : 0;
                    $individual_hour_22 = array_key_exists($size_key, $hour_22) ? $hour_22[$size_key] : 0;
                    $individual_hour_23 = array_key_exists($size_key, $hour_23) ? $hour_23[$size_key] : 0;

                    $hourly_total_production_qty = $individual_hour_8 + $individual_hour_9 + $individual_hour_10 + $individual_hour_11 + $individual_hour_12 +
                        $individual_hour_13 + $individual_hour_14 + $individual_hour_15 + $individual_hour_16 + $individual_hour_17 + $individual_hour_18 +
                        $individual_hour_19 + $individual_hour_20 + $individual_hour_21 + $individual_hour_22 + $individual_hour_23;
                    $individual_production_qty = ($entry_format == ManualHourlySewingProduction::HOURLY_ENTRY_FORMAT) ? $hourly_total_production_qty : $production_qty[$size_key];
                    $individual_challan_no = array_key_exists($size_key, $challan_no) ? $challan_no[$size_key] : 0;
                    $individual_rejection_qty = array_key_exists($size_key, $rejection_qty) ? $rejection_qty[$size_key] : 0;
                    $individual_alter_qty = array_key_exists($size_key, $alter_qty) ? $alter_qty[$size_key] : 0;
                    $manual_sewing_output_production = new ManualHourlySewingProduction([
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
                        'floor_id' => $floor_id,
                        'line_id' => $line_id,
                        'sub_sewing_floor_id' => $sub_sewing_floor_id,
                        'sub_sewing_line_id' => $sub_sewing_line_id,
                        'production_qty' => $individual_production_qty,
                        'rejection_qty' => isset($individual_rejection_qty) ? $individual_rejection_qty : 0,
                        'alter_qty' => isset($individual_alter_qty) ? $individual_alter_qty : 0,
                        'challan_no' => $individual_challan_no,
                        'supervisor' => $supervisor,
                        'produced_by' => $produced_by,
                        'reporting_hour' => $reporting_hour,
                        'remarks' => $remarks,
                        'entry_format' => $entry_format,
                        'hour_8' => isset($individual_hour_8) ? $individual_hour_8 : 0,
                        'hour_9' => isset($individual_hour_9) ? $individual_hour_9 : 0,
                        'hour_10' => isset($individual_hour_10) ? $individual_hour_10 : 0,
                        'hour_11' => isset($individual_hour_11) ? $individual_hour_11 : 0,
                        'hour_12' => isset($individual_hour_12) ? $individual_hour_12 : 0,
                        'hour_13' => isset($individual_hour_13) ? $individual_hour_13 : 0,
                        'hour_14' => isset($individual_hour_14) ? $individual_hour_14 : 0,
                        'hour_15' => isset($individual_hour_15) ? $individual_hour_15 : 0,
                        'hour_16' => isset($individual_hour_16) ? $individual_hour_16 : 0,
                        'hour_17' => isset($individual_hour_17) ? $individual_hour_17 : 0,
                        'hour_18' => isset($individual_hour_18) ? $individual_hour_18 : 0,
                        'hour_19' => isset($individual_hour_19) ? $individual_hour_19 : 0,
                        'hour_20' => isset($individual_hour_20) ? $individual_hour_20 : 0,
                        'hour_21' => isset($individual_hour_21) ? $individual_hour_21 : 0,
                        'hour_22' => isset($individual_hour_22) ? $individual_hour_22 : 0,
                        'hour_23' => isset($individual_hour_23) ? $individual_hour_23 : 0,
                    ]);
                    $manual_sewing_output_production->save();
                }
            } elseif ($color_id && is_array($color_id) && count($color_id) && $production_qty && is_array($production_qty)) {
                // for color wise data rule
                foreach ($color_id as $color_key => $c_id) {
                    if (!array_key_exists($color_key, $production_qty) || !$production_qty[$color_key] || $production_qty[$color_key] <= 0) {
                        continue;
                    }
                    $individual_hour_8  = array_key_exists($color_key, $hour_8) ? $hour_8[$color_key] : 0;
                    $individual_hour_9  = array_key_exists($color_key, $hour_9) ? $hour_9[$color_key] : 0;
                    $individual_hour_10 = array_key_exists($color_key, $hour_10) ? $hour_10[$color_key] : 0;
                    $individual_hour_11 = array_key_exists($color_key, $hour_11) ? $hour_11[$color_key] : 0;
                    $individual_hour_12 = array_key_exists($color_key, $hour_12) ? $hour_12[$color_key] : 0;
                    $individual_hour_13 = array_key_exists($color_key, $hour_13) ? $hour_13[$color_key] : 0;
                    $individual_hour_14 = array_key_exists($color_key, $hour_14) ? $hour_14[$color_key] : 0;
                    $individual_hour_15 = array_key_exists($color_key, $hour_15) ? $hour_15[$color_key] : 0;
                    $individual_hour_16 = array_key_exists($color_key, $hour_16) ? $hour_16[$color_key] : 0;
                    $individual_hour_17 = array_key_exists($color_key, $hour_17) ? $hour_17[$color_key] : 0;
                    $individual_hour_18 = array_key_exists($color_key, $hour_18) ? $hour_18[$color_key] : 0;
                    $individual_hour_19 = array_key_exists($color_key, $hour_19) ? $hour_19[$color_key] : 0;
                    $individual_hour_20 = array_key_exists($color_key, $hour_20) ? $hour_20[$color_key] : 0;
                    $individual_hour_21 = array_key_exists($color_key, $hour_21) ? $hour_21[$color_key] : 0;
                    $individual_hour_22 = array_key_exists($color_key, $hour_22) ? $hour_22[$color_key] : 0;
                    $individual_hour_23 = array_key_exists($color_key, $hour_23) ? $hour_23[$color_key] : 0;

                    $hourly_total_production_qty = $individual_hour_8 + $individual_hour_9 + $individual_hour_10 + $individual_hour_11 + $individual_hour_12 +
                        $individual_hour_13 + $individual_hour_14 + $individual_hour_15 + $individual_hour_16 + $individual_hour_17 + $individual_hour_18 +
                        $individual_hour_19 + $individual_hour_20 + $individual_hour_21 + $individual_hour_22 + $individual_hour_23;
                    $individual_production_qty = ($entry_format == ManualHourlySewingProduction::HOURLY_ENTRY_FORMAT) ? $hourly_total_production_qty : $production_qty[$color_key];
                    $individual_challan_no = array_key_exists($color_key, $challan_no) ? $challan_no[$color_key] : 0;
                    $individual_rejection_qty = array_key_exists($color_key, $rejection_qty) ? $rejection_qty[$color_key] : 0;
                    $individual_alter_qty = array_key_exists($color_key, $alter_qty) ? $alter_qty[$color_key] : 0;
                    $manual_sewing_output_production = new ManualHourlySewingProduction([
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
                        'floor_id' => $floor_id,
                        'line_id' => $line_id,
                        'sub_sewing_floor_id' => $sub_sewing_floor_id,
                        'sub_sewing_line_id' => $sub_sewing_line_id,
                        'production_qty' => $individual_production_qty,
                        'rejection_qty' => isset($individual_rejection_qty) ? $individual_rejection_qty : 0,
                        'alter_qty' => isset($individual_alter_qty) ? $individual_alter_qty : 0,
                        'challan_no' => $individual_challan_no,
                        'supervisor' => $supervisor,
                        'produced_by' => $produced_by,
                        'reporting_hour' => $reporting_hour,
                        'remarks' => $remarks,
                        'entry_format' => $entry_format,
                        'hour_8' => isset($individual_hour_8) ? $individual_hour_8 : 0,
                        'hour_9' => isset($individual_hour_9) ? $individual_hour_9 : 0,
                        'hour_10' => isset($individual_hour_10) ? $individual_hour_10 : 0,
                        'hour_11' => isset($individual_hour_11) ? $individual_hour_11 : 0,
                        'hour_12' => isset($individual_hour_12) ? $individual_hour_12 : 0,
                        'hour_13' => isset($individual_hour_13) ? $individual_hour_13 : 0,
                        'hour_14' => isset($individual_hour_14) ? $individual_hour_14 : 0,
                        'hour_15' => isset($individual_hour_15) ? $individual_hour_15 : 0,
                        'hour_16' => isset($individual_hour_16) ? $individual_hour_16 : 0,
                        'hour_17' => isset($individual_hour_17) ? $individual_hour_17 : 0,
                        'hour_18' => isset($individual_hour_18) ? $individual_hour_18 : 0,
                        'hour_19' => isset($individual_hour_19) ? $individual_hour_19 : 0,
                        'hour_20' => isset($individual_hour_20) ? $individual_hour_20 : 0,
                        'hour_21' => isset($individual_hour_21) ? $individual_hour_21 : 0,
                        'hour_22' => isset($individual_hour_22) ? $individual_hour_22 : 0,
                        'hour_23' => isset($individual_hour_23) ? $individual_hour_23 : 0,
                    ]);
                    $manual_sewing_output_production->save();
                }
            } elseif (!$color_id && !$size_id && $production_qty && !is_array($production_qty)) {
                // for order wise data
                $hourly_total_production_qty = $hour_8 + $hour_9 + $hour_10 + $hour_11 + $hour_12 +
                    $hour_13 + $hour_14 + $hour_15 + $hour_16 + $hour_17 + $hour_18 +
                    $hour_19 + $hour_20 + $hour_21 + $hour_22 + $hour_23;
                $individual_production_qty = ($entry_format == ManualHourlySewingProduction::HOURLY_ENTRY_FORMAT) ? $hourly_total_production_qty : $production_qty;

                $manual_sewing_output_production = new ManualHourlySewingProduction([
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
                    'floor_id' => $floor_id,
                    'line_id' => $line_id,
                    'sub_sewing_floor_id' => $sub_sewing_floor_id,
                    'sub_sewing_line_id' => $sub_sewing_line_id,
                    'production_qty' => $individual_production_qty,
                    'rejection_qty' => isset($rejection_qty) ? $rejection_qty : 0,
                    'alter_qty' => isset($alter_qty) ? $alter_qty : 0,
                    'challan_no' => $challan_no,
                    'supervisor' => $supervisor,
                    'produced_by' => $produced_by,
                    'reporting_hour' => $reporting_hour,
                    'remarks' => $remarks,
                    'entry_format' => $entry_format,
                    'hour_8' => $hour_8,
                    'hour_9' => $hour_9,
                    'hour_10' => $hour_10,
                    'hour_11' => $hour_11,
                    'hour_12' => $hour_12,
                    'hour_13' => $hour_13,
                    'hour_14' => $hour_14,
                    'hour_15' => $hour_15,
                    'hour_16' => $hour_16,
                    'hour_17' => $hour_17,
                    'hour_18' => $hour_18,
                    'hour_19' => $hour_19,
                    'hour_20' => $hour_20,
                    'hour_21' => $hour_21,
                    'hour_22' => $hour_22,
                    'hour_23' => $hour_23,
                ]);
                $manual_sewing_output_production->save();
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
