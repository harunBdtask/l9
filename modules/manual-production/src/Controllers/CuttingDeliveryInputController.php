<?php

namespace SkylarkSoft\GoRMG\ManualProduction\Controllers;

use App\Http\Controllers\Controller;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Response;
use SkylarkSoft\GoRMG\ManualProduction\Controllers\Search\SearchController;
use SkylarkSoft\GoRMG\ManualProduction\Models\ManualCutToInputDelivery;
use SkylarkSoft\GoRMG\ManualProduction\Requests\ManualCutToInputDeliveryRequest;

class CuttingDeliveryInputController extends Controller
{
    public function index()
    {
        return view("manual-production::cutting-delivery-input-challan.index");
    }

    public function store(ManualCutToInputDeliveryRequest $request): JsonResponse
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
            $production_qty = $request->production_qty ?? 0;
            $bundle_qty = $request->bundle_qty ?? 0;
            $challan_no = $request->challan_no ?? null;
            $remarks = $request->remarks ?? null;

            if ($size_id && is_array($size_id) && count($size_id) && $production_qty && is_array($production_qty)) {
                // for size wise data
                foreach ($size_id as $size_key => $s_id) {
                    if (!array_key_exists($size_key, $production_qty) || !$production_qty[$size_key] || $production_qty[$size_key] <= 0) {
                        continue;
                    }
                    $individual_production_qty = $production_qty[$size_key];
                    $individual_bundle_qty = array_key_exists($size_key, $bundle_qty) ? $bundle_qty[$size_key] : 0;
                    $individual_challan_no = array_key_exists($size_key, $challan_no) ? $challan_no[$size_key] : null;
                    $manual_cut_to_input_delivery = new ManualCutToInputDelivery([
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
                        'production_qty' => $individual_production_qty,
                        'bundle_qty' => isset($individual_bundle_qty) ? $individual_bundle_qty : 0,
                        'challan_no' => $individual_challan_no,
                        'remarks' => $remarks,
                    ]);
                    $manual_cut_to_input_delivery->save();
                }
            } elseif ($color_id && is_array($color_id) && count($color_id) && $production_qty && is_array($production_qty)) {
                // for color wise data rule
                foreach ($color_id as $color_key => $c_id) {
                    if (!array_key_exists($color_key, $production_qty) || !$production_qty[$color_key] || $production_qty[$color_key] <= 0) {
                        continue;
                    }
                    $individual_production_qty = $production_qty[$color_key];
                    $individual_bundle_qty = array_key_exists($color_key, $bundle_qty) ? $bundle_qty[$color_key] : 0;
                    $individual_challan_no = array_key_exists($color_key, $challan_no) ? $challan_no[$color_key] : 0;
                    $manual_cut_to_input_delivery = new ManualCutToInputDelivery([
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
                        'production_qty' => $individual_production_qty,
                        'bundle_qty' => isset($individual_bundle_qty) ? $individual_bundle_qty : 0,
                        'challan_no' => $individual_challan_no,
                        'remarks' => $remarks,
                    ]);
                    $manual_cut_to_input_delivery->save();
                }
            } elseif (!$color_id && !$size_id && $production_qty && !is_array($production_qty)) {
                // for order wise data
                $manual_cut_to_input_delivery = new ManualCutToInputDelivery([
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
                    'production_qty' => $production_qty,
                    'challan_no' => $challan_no,
                    'bundle_qty' => isset($bundle_qty) ? $bundle_qty : 0,
                    'remarks' => $remarks,
                ]);
                $manual_cut_to_input_delivery->save();
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
            ], 500);
        }
    }
}
