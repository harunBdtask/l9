<?php

namespace SkylarkSoft\GoRMG\ManualProduction\Controllers;

use App\Http\Controllers\Controller;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use SkylarkSoft\GoRMG\ManualProduction\Controllers\Search\SearchController;
use SkylarkSoft\GoRMG\ManualProduction\Models\ManualCuttingProduction;
use SkylarkSoft\GoRMG\ManualProduction\Models\ManualEmblIssueProduction;
use SkylarkSoft\GoRMG\ManualProduction\Models\ManualEmblReceiveProduction;
use SkylarkSoft\GoRMG\ManualProduction\Requests\ManualEmbellishmentIssueRequest;
use SkylarkSoft\GoRMG\ManualProduction\Requests\ManualEmbellishmentReceiveRequest;

class EmbellishmentProductionController extends Controller
{
    public function issueEntry()
    {
        return view("manual-production::embelishment-production.issue-entry");
    }

    public function issueStore(ManualEmbellishmentIssueRequest $request)
    {
        try {
            DB::beginTransaction();
            $production_date = $request->production_date ?? null;
            $embl_name = $request->embl_name ?? null;
            $embl_type = $request->embl_type ?? null;
            $source = $request->source ?? null;
            $factory_id = $request->factory_id ?? null;
            $subcontract_factory_id = $request->subcontract_factory_id ?? null;
            $buyer_id = $request->buyer_id ?? null;
            $order_id = $request->order_id ?? null;
            $garments_item_id = $request->garments_item_id ?? null;
            $purchase_order_id = $request->purchase_order_id ?? null;
            $color_id = $request->color_id ?? null;
            $size_id = $request->size_id ?? null;
            $sub_embl_floor_id = $request->sub_embl_floor_id ?? null;
            $no_of_bags = $request->no_of_bags ?? null;
            $production_qty = $request->production_qty ?? null;
            $challan_no = $request->challan_no ?? null;
            $remarks = $request->remarks ?? null;

            if ($size_id && is_array($size_id) && count($size_id) && $production_qty && is_array($production_qty)) {
                // for size wise data
                foreach ($size_id as $size_key => $s_id) {
                    if (!array_key_exists($size_key, $production_qty) || !$production_qty[$size_key] || $production_qty[$size_key] <= 0) {
                        continue;
                    }
                    $individual_no_of_bags = array_key_exists($size_key, $no_of_bags) ? $no_of_bags[$size_key] : 0;
                    $individual_production_qty = $production_qty[$size_key];
                    $individual_challan_no = array_key_exists($size_key, $challan_no) ? $challan_no[$size_key] : 0;
                    $manual_embl_issue_production = new ManualEmblIssueProduction([
                        'production_date' => $production_date,
                        'embl_name' => $embl_name,
                        'embl_type' => $embl_type,
                        'source' => $source,
                        'factory_id' => $factory_id,
                        'subcontract_factory_id' => $subcontract_factory_id,
                        'buyer_id' => $buyer_id,
                        'order_id' => $order_id,
                        'garments_item_id' => $garments_item_id,
                        'purchase_order_id' => $purchase_order_id,
                        'color_id' => $color_id,
                        'size_id' => $s_id,
                        'sub_embl_floor_id' => $sub_embl_floor_id,
                        'no_of_bags' => isset($individual_no_of_bags) ? $individual_no_of_bags : 0,
                        'production_qty' => $individual_production_qty,
                        'challan_no' => $individual_challan_no,
                        'remarks' => $remarks,
                    ]);
                    $manual_embl_issue_production->save();
                }
            } elseif ($color_id && is_array($color_id) && count($color_id) && $production_qty && is_array($production_qty)) {
                // for color wise data rule
                foreach ($color_id as $color_key => $c_id) {
                    if (!array_key_exists($color_key, $production_qty) || !$production_qty[$color_key] || $production_qty[$color_key] <= 0) {
                        continue;
                    }
                    $individual_no_of_bags = array_key_exists($color_key, $no_of_bags) ? $no_of_bags[$color_key] : 0;
                    $individual_production_qty = $production_qty[$color_key];
                    $individual_challan_no = array_key_exists($color_key, $challan_no) ? $challan_no[$color_key] : 0;
                    $manual_embl_issue_production = new ManualEmblIssueProduction([
                        'production_date' => $production_date,
                        'embl_name' => $embl_name,
                        'embl_type' => $embl_type,
                        'source' => $source,
                        'factory_id' => $factory_id,
                        'subcontract_factory_id' => $subcontract_factory_id,
                        'buyer_id' => $buyer_id,
                        'order_id' => $order_id,
                        'garments_item_id' => $garments_item_id,
                        'purchase_order_id' => $purchase_order_id,
                        'color_id' => $c_id,
                        'size_id' => $size_id,
                        'sub_embl_floor_id' => $sub_embl_floor_id,
                        'no_of_bags' => isset($individual_no_of_bags) ? $individual_no_of_bags : 0,
                        'production_qty' => $individual_production_qty,
                        'challan_no' => $individual_challan_no,
                        'remarks' => $remarks,
                    ]);
                    $manual_embl_issue_production->save();
                }
            } elseif (!$color_id && !$size_id && $production_qty && !is_array($production_qty)) {
                // for order wise data
                $manual_embl_issue_production = new ManualEmblIssueProduction([
                    'production_date' => $production_date,
                    'embl_name' => $embl_name,
                    'embl_type' => $embl_type,
                    'source' => $source,
                    'factory_id' => $factory_id,
                    'subcontract_factory_id' => $subcontract_factory_id,
                    'buyer_id' => $buyer_id,
                    'order_id' => $order_id,
                    'garments_item_id' => $garments_item_id,
                    'purchase_order_id' => $purchase_order_id,
                    'color_id' => $color_id,
                    'size_id' => $size_id,
                    'sub_embl_floor_id' => $sub_embl_floor_id,
                    'no_of_bags' => isset($no_of_bags) ? $no_of_bags : 0,
                    'production_qty' => $production_qty,
                    'challan_no' => $challan_no,
                    'remarks' => $remarks,
                ]);
                $manual_embl_issue_production->save();
            } else {
                // for indistinctive data do not save any data
            }
            $data = [];
            if ($purchase_order_id && $garments_item_id && $embl_name) {
                $data = SearchController::getSingleSearchData($purchase_order_id, $garments_item_id, $embl_name);
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

    public function receiveEntry()
    {
        return view("manual-production::embelishment-production.receive-entry");
    }

    public function receiveStore(ManualEmbellishmentReceiveRequest $request)
    {
        try {
            DB::beginTransaction();
            $production_date = $request->production_date ?? null;
            $embl_name = $request->embl_name ?? null;
            $embl_type = $request->embl_type ?? null;
            $source = $request->source ?? null;
            $factory_id = $request->factory_id ?? null;
            $subcontract_factory_id = $request->subcontract_factory_id ?? null;
            $buyer_id = $request->buyer_id ?? null;
            $order_id = $request->order_id ?? null;
            $garments_item_id = $request->garments_item_id ?? null;
            $purchase_order_id = $request->purchase_order_id ?? null;
            $color_id = $request->color_id ?? null;
            $size_id = $request->size_id ?? null;
            $sub_embl_floor_id = $request->sub_embl_floor_id ?? null;
            $no_of_bags = $request->no_of_bags ?? null;
            $production_qty = $request->production_qty ?? null;
            $rejection_qty = $request->rejection_qty ?? null;
            $challan_no = $request->challan_no ?? null;
            $remarks = $request->remarks ?? null;

            if ($size_id && is_array($size_id) && count($size_id) && $production_qty && is_array($production_qty)) {
                // for size wise data
                foreach ($size_id as $size_key => $s_id) {
                    if (!array_key_exists($size_key, $production_qty) || !$production_qty[$size_key] || $production_qty[$size_key] <= 0) {
                        continue;
                    }
                    $individual_no_of_bags = array_key_exists($size_key, $no_of_bags) ? $no_of_bags[$size_key] : 0;
                    $individual_production_qty = $production_qty[$size_key];
                    $individual_challan_no = array_key_exists($size_key, $challan_no) ? $challan_no[$size_key] : 0;
                    $individual_rejection_qty = array_key_exists($size_key, $rejection_qty) ? $rejection_qty[$size_key] : 0;
                    $manual_embl_receive_production = new ManualEmblReceiveProduction([
                        'production_date' => $production_date,
                        'embl_name' => $embl_name,
                        'embl_type' => $embl_type,
                        'source' => $source,
                        'factory_id' => $factory_id,
                        'subcontract_factory_id' => $subcontract_factory_id,
                        'buyer_id' => $buyer_id,
                        'order_id' => $order_id,
                        'garments_item_id' => $garments_item_id,
                        'purchase_order_id' => $purchase_order_id,
                        'color_id' => $color_id,
                        'size_id' => $s_id,
                        'sub_embl_floor_id' => $sub_embl_floor_id,
                        'no_of_bags' => isset($individual_no_of_bags) ? $individual_no_of_bags : 0,
                        'production_qty' => $individual_production_qty,
                        'rejection_qty' => isset($individual_rejection_qty) ? $individual_rejection_qty : 0,
                        'challan_no' => $individual_challan_no,
                        'remarks' => $remarks,
                    ]);
                    $manual_embl_receive_production->save();
                }
            } elseif ($color_id && is_array($color_id) && count($color_id) && $production_qty && is_array($production_qty)) {
                // for color wise data rule
                foreach ($color_id as $color_key => $c_id) {
                    if (!array_key_exists($color_key, $production_qty) || !$production_qty[$color_key] || $production_qty[$color_key] <= 0) {
                        continue;
                    }
                    $individual_no_of_bags = array_key_exists($color_key, $no_of_bags) ? $no_of_bags[$color_key] : 0;
                    $individual_production_qty = $production_qty[$color_key];
                    $individual_challan_no = array_key_exists($color_key, $challan_no) ? $challan_no[$color_key] : 0;
                    $individual_rejection_qty = array_key_exists($color_key, $rejection_qty) ? $rejection_qty[$color_key] : 0;
                    $manual_embl_receive_production = new ManualEmblReceiveProduction([
                        'production_date' => $production_date,
                        'embl_name' => $embl_name,
                        'embl_type' => $embl_type,
                        'source' => $source,
                        'factory_id' => $factory_id,
                        'subcontract_factory_id' => $subcontract_factory_id,
                        'buyer_id' => $buyer_id,
                        'order_id' => $order_id,
                        'garments_item_id' => $garments_item_id,
                        'purchase_order_id' => $purchase_order_id,
                        'color_id' => $c_id,
                        'size_id' => $size_id,
                        'sub_embl_floor_id' => $sub_embl_floor_id,
                        'no_of_bags' => isset($individual_no_of_bags) ? $individual_no_of_bags : 0,
                        'production_qty' => $individual_production_qty,
                        'rejection_qty' => isset($individual_rejection_qty) ? $individual_rejection_qty : 0,
                        'challan_no' => $individual_challan_no,
                        'remarks' => $remarks,
                    ]);
                    $manual_embl_receive_production->save();
                }
            } elseif (!$color_id && !$size_id && $production_qty && !is_array($production_qty)) {
                // for order wise data
                $manual_embl_receive_production = new ManualEmblReceiveProduction([
                    'production_date' => $production_date,
                    'embl_name' => $embl_name,
                    'embl_type' => $embl_type,
                    'source' => $source,
                    'factory_id' => $factory_id,
                    'subcontract_factory_id' => $subcontract_factory_id,
                    'buyer_id' => $buyer_id,
                    'order_id' => $order_id,
                    'garments_item_id' => $garments_item_id,
                    'purchase_order_id' => $purchase_order_id,
                    'color_id' => $color_id,
                    'size_id' => $size_id,
                    'sub_embl_floor_id' => $sub_embl_floor_id,
                    'no_of_bags' => isset($no_of_bags) ? $no_of_bags : 0,
                    'production_qty' => $production_qty,
                    'rejection_qty' => isset($rejection_qty) ? $rejection_qty : 0,
                    'challan_no' => $challan_no,
                    'remarks' => $remarks,
                ]);
                $manual_embl_receive_production->save();
            } else {
                // for indistinctive data do not save any data
            }
            $data = [];
            if ($purchase_order_id && $garments_item_id && $embl_name) {
                $data = SearchController::getSingleSearchData($purchase_order_id, $garments_item_id, $embl_name);
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
