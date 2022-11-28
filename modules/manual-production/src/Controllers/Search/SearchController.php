<?php

namespace SkylarkSoft\GoRMG\ManualProduction\Controllers\Search;

use App\Http\Controllers\Controller;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use SkylarkSoft\GoRMG\ManualProduction\Models\ManualCuttingProduction;
use SkylarkSoft\GoRMG\ManualProduction\Models\ManualCutToInputDelivery;
use SkylarkSoft\GoRMG\ManualProduction\Models\ManualEmblIssueProduction;
use SkylarkSoft\GoRMG\ManualProduction\Models\ManualEmblReceiveProduction;
use SkylarkSoft\GoRMG\ManualProduction\Models\ManualHourlySewingProduction;
use SkylarkSoft\GoRMG\ManualProduction\Models\ManualInspectionProduction;
use SkylarkSoft\GoRMG\ManualProduction\Models\ManualIronProduction;
use SkylarkSoft\GoRMG\ManualProduction\Models\ManualPolyPackingProduction;
use SkylarkSoft\GoRMG\ManualProduction\Models\ManualSewingInputProduction;
use SkylarkSoft\GoRMG\ManualProduction\Models\ManualShipmentProduction;
use SkylarkSoft\GoRMG\ManualProduction\Models\Subcontract\SubcontractCuttingFloor;
use SkylarkSoft\GoRMG\ManualProduction\Models\Subcontract\SubcontractCuttingTable;
use SkylarkSoft\GoRMG\ManualProduction\Models\Subcontract\SubcontractEmbellishmentFloor;
use SkylarkSoft\GoRMG\ManualProduction\Models\Subcontract\SubcontractFactoryProfile;
use SkylarkSoft\GoRMG\ManualProduction\Models\Subcontract\SubcontractFinishingFloor;
use SkylarkSoft\GoRMG\ManualProduction\Models\Subcontract\SubcontractFinishingTable;
use SkylarkSoft\GoRMG\ManualProduction\Models\Subcontract\SubcontractSewingFloor;
use SkylarkSoft\GoRMG\ManualProduction\Models\Subcontract\SubcontractSewingLine;
use SkylarkSoft\GoRMG\Merchandising\Models\Order;
use SkylarkSoft\GoRMG\Merchandising\Models\PoColorSizeBreakdown;
use SkylarkSoft\GoRMG\Merchandising\Models\PurchaseOrder;
use SkylarkSoft\GoRMG\Merchandising\Models\PurchaseOrderDetail;
use SkylarkSoft\GoRMG\SystemSettings\Models\Color;
use SkylarkSoft\GoRMG\SystemSettings\Models\CuttingFloor;
use SkylarkSoft\GoRMG\SystemSettings\Models\CuttingTable;
use SkylarkSoft\GoRMG\SystemSettings\Models\Floor;
use SkylarkSoft\GoRMG\SystemSettings\Models\Line;

class SearchController extends Controller
{
    public function selection(Request $request): JsonResponse
    {
        try {
            $search_by = $request->search_by ?? null;
            $search_query = $request->search_q ?? null;
            $buyer_id = $request->buyer_id ?? null;
            switch ($search_by) {
                case 'order_no':
                    $response = $this->orderNoSearch($search_query, $buyer_id);
                    break;
                case 'style_name':
                    $response = $this->styleNameSearch($search_query, $buyer_id);
                    break;
                case 'uniq_id':
                    $response = $this->styleUniqueIdSearch($search_query, $buyer_id);
                    break;
                case 'po_no':
                    $response = $this->poNoSearch($search_query, $buyer_id);
                    break;
                case 'file_no':
                    $response = $this->poFileNoSearch($search_query, $buyer_id);
                    break;
                case 'internal_ref_no':
                    $response = $this->poInternalRefNoSearch($search_query, $buyer_id);
                    break;
                default:
                    $response = [
                        'data' => [],
                        'error' => null,
                        'message' => 'Success',
                        'status' => 200,
                    ];
                    break;
            }

            return response()->json($response);
        } catch (Exception $e) {
            $response = [
                'data' => [],
                'error' => $e->getMessage(),
                'message' => 'Something went wrong',
                'status' => 500,
            ];
            return response()->json($response);
        }
    }

    private function orderNoSearch($search_query = '', $buyer_id = ''): array
    {
        try {
            $data = PurchaseOrder::query()
                ->select('id', 'po_no')
                ->where('order_status', 'Confirm')
                ->when($buyer_id != '', function ($q) use ($buyer_id) {
                    return $q->where('buyer_id', $buyer_id);
                })
                ->when($search_query != '', function ($q) use ($search_query) {
                    return $q->where('po_no', 'LIKE', $search_query . '%');
                })
                //                ->limit(20)
                ->get()
                ->map(function ($item, $key) {
                    $data['id'] = $item->id;
                    $data['text'] = $item->po_no;
                    return $data;
                });
            return [
                'data' => $data,
                'error' => null,
                'message' => 'Success',
                'status' => 200,
            ];
        } catch (Exception $e) {
            return [
                'data' => [],
                'error' => $e->getMessage(),
                'message' => 'Something went wrong',
                'status' => 500,
            ];
        }
    }

    private function styleNameSearch($search_query = '', $buyer_id = ''): array
    {
        try {
            $order_ids = ($search_query != '' || $buyer_id != '') ? Order::query()
                ->when($buyer_id != '', function ($q) use ($buyer_id) {
                    return $q->where('buyer_id', $buyer_id);
                })
                ->when($search_query != '', function ($q) use ($search_query) {
                    return $q->where('job_no', 'LIKE', $search_query . '%');
                })
                ->pluck('id')
                ->toArray() : [];
            $data = [];
            if ($order_ids && is_array($order_ids) && count($order_ids)) {
                $data = PurchaseOrder::query()
                ->with('order:id,style_name')
                ->select('id', 'order_id', 'po_no')
                ->where('order_status', 'Confirm')
                ->when(($order_ids && is_array($order_ids) && count($order_ids)), function ($q) use ($order_ids) {
                    return $q->whereIn('order_id', $order_ids);
                })
                //                ->limit(20)
                ->get()
                ->map(function ($item, $key) {
                    $data['id'] = $item->id;
                    $data['text'] = $item->order->style_name.'['.$item->po_no.']';
                    return $data;
                });
            }
            
            return [
                'data' => $data,
                'error' => null,
                'message' => 'Success',
                'status' => 200,
            ];
        } catch (Exception $e) {
            return [
                'data' => [],
                'error' => $e->getMessage(),
                'message' => 'Something went wrong',
                'status' => 500,
            ];
        }
    }

    private function styleUniqueIdSearch($search_query = '', $buyer_id = ''): array
    {
        try {
            $order_ids = ($search_query != '' || $buyer_id != '') ? Order::query()
                ->when($buyer_id != '', function ($q) use ($buyer_id) {
                    return $q->where('buyer_id', $buyer_id);
                })
                ->when($search_query != '', function ($q) use ($search_query) {
                    return $q->where('job_no', 'LIKE', $search_query . '%');
                })
                ->pluck('id')
                ->toArray() : [];
            
            $data = [];
            if ($order_ids && is_array($order_ids) && count($order_ids)) {
                $data = PurchaseOrder::query()
                ->with('order:id,job_no')
                ->select('id', 'order_id', 'po_no')
                ->where('order_status', 'Confirm')
                ->when(($order_ids && is_array($order_ids) && count($order_ids)), function ($q) use ($order_ids) {
                    return $q->whereIn('order_id', $order_ids);
                })
                //                ->limit(20)
                ->get()
                ->map(function ($item, $key) {
                    $data['id'] = $item->id;
                    $data['text'] = $item->order->job_no.'['.$item->po_no.']';
                    return $data;
                });
            }

            return [
                'data' => $data,
                'error' => null,
                'message' => 'Success',
                'status' => 200,
            ];

        } catch (Exception $e) {
            return [
                'data' => [],
                'error' => $e->getMessage(),
                'message' => 'Something went wrong',
                'status' => 500,
            ];
        }
    }

    private function poNoSearch($search_query = '', $buyer_id = ''): array
    {
        try {
            $data = PurchaseOrder::query()
                ->select('id', 'po_no')
                ->where('order_status', 'Confirm')
                ->when($buyer_id != '', function ($q) use ($buyer_id) {
                    return $q->where('buyer_id', $buyer_id);
                })
                ->when($search_query != '', function ($q) use ($search_query) {
                    return $q->where('po_no', 'LIKE', $search_query . '%');
                })
                //                ->limit(20)
                ->get()
                ->map(function ($item, $key) {
                    $data['id'] = $item->id;
                    $data['text'] = $item->po_no;
                    return $data;
                });

            return [
                'data' => $data,
                'error' => null,
                'message' => 'Success',
                'status' => 200,
            ];
        } catch (Exception $e) {
            return [
                'data' => [],
                'error' => $e->getMessage(),
                'message' => 'Something went wrong',
                'status' => 500,
            ];
        }
    }

    private function poFileNoSearch($search_query = '', $buyer_id = ''): array
    {
        try {
            $data = PurchaseOrder::query()
                ->select('id', 'comm_file_no')
                ->where('order_status', 'Confirm')
                ->whereNotNull('comm_file_no')
                ->when($buyer_id != '', function ($q) use ($buyer_id) {
                    return $q->where('buyer_id', $buyer_id);
                })
                ->when($search_query != '', function ($q) use ($search_query) {
                    return $q->where('comm_file_no', 'LIKE', $search_query . '%');
                })
                //                ->limit(20)
                ->get()
                ->map(function ($item, $key) {
                    $data['id'] = $item->id;
                    $data['text'] = $item->comm_file_no;
                    return $data;
                });

            return [
                'data' => $data,
                'error' => null,
                'message' => 'Success',
                'status' => 200,
            ];
        } catch (Exception $e) {
            return [
                'data' => [],
                'error' => $e->getMessage(),
                'message' => 'Something went wrong',
                'status' => 500,
            ];
        }
    }

    private function poInternalRefNoSearch($search_query = '', $buyer_id = ''): array
    {
        try {
            $data = PurchaseOrder::query()
                ->select('id', 'internal_ref_no')
                ->where('order_status', 'Confirm')
                ->whereNotNull('internal_ref_no')
                ->when($buyer_id != '', function ($q) use ($buyer_id) {
                    return $q->where('buyer_id', $buyer_id);
                })
                ->when($search_query != '', function ($q) use ($search_query) {
                    return $q->where('internal_ref_no', 'LIKE', $search_query . '%');
                })
                //                ->limit(20)
                ->get()
                ->map(function ($item, $key) {
                    $data['id'] = $item->id;
                    $data['text'] = $item->internal_ref_no;
                    return $data;
                });

            return [
                'data' => $data,
                'error' => null,
                'message' => 'Success',
                'status' => 200,
            ];
        } catch (Exception $e) {
            return [
                'data' => [],
                'error' => $e->getMessage(),
                'message' => 'Something went wrong',
                'status' => 500,
            ];
        }
    }

    public function searchList(Request $request): array
    {
        try {
            $buyer_id = $request->buyer_id ?? null;
            $search_by = $request->search_by ?? null;
            $purchase_order_id = $request->search_q ?? null;
            $embl_name = $request->embl_name ?? null;
            $from_date = $request->from_date ?? null;
            $to_date = $request->to_date ?? null;
            $data = [];
            if ($purchase_order_id) {
                $data = PoColorSizeBreakdown::query()
                    ->whereNull('deleted_at')
                    ->where('purchase_order_id', $purchase_order_id)
                    ->get()
                    ->map(function ($item, $key) use ($embl_name) {
                        $color_size_qty_breakdown = $item->quantity_matrix;
                        $plan_cut_qty = $color_size_qty_breakdown ? collect($color_size_qty_breakdown)->where('particular', "Plan Cut Qty.")->sum('value') : 0;
                        $purchase_order_id = $item->purchase_order_id;
                        $garments_item_id = $item->garments_item_id;
                        $po_qty = 0;
                        $color_details = [];
                        $po_details = PurchaseOrderDetail::query()
                            ->where([
                                'purchase_order_id' => $purchase_order_id,
                                'garments_item_id' => $garments_item_id,
                            ])
                            ->where('quantity', '>', 0)
                            ->get();

                        $manual_cutting_production_query = ManualCuttingProduction::query()
                            ->where([
                                'purchase_order_id' => $purchase_order_id,
                                'garments_item_id' => $garments_item_id,
                            ]);
                        $manual_total_cutting_production_query = clone $manual_cutting_production_query;

                        $total_cut_qty = $manual_total_cutting_production_query->sum('production_qty');
                        $total_cutting_rejection_qty = $manual_total_cutting_production_query->sum('rejection_qty');

                        $manual_color_embl_issue_query = null;
                        $manual_color_size_embl_issue_query = null;
                        $manual_color_embl_receive_query = null;
                        $manual_color_size_embl_receive_query = null;
                        if ($embl_name) {
                            $manual_embl_issue_query = ManualEmblIssueProduction::query()
                                ->where([
                                    'purchase_order_id' => $purchase_order_id,
                                    'garments_item_id' => $garments_item_id,
                                    'embl_name' => $embl_name,
                                ]);
                            $manual_total_embl_issue_query = clone $manual_embl_issue_query;

                            $total_embl_issue_qty = $manual_total_embl_issue_query->sum('production_qty');
                            $embl_issue_balance_qty = $total_cut_qty - $total_embl_issue_qty;

                            $manual_embl_receive_query = ManualEmblReceiveProduction::query()
                                ->where([
                                    'purchase_order_id' => $purchase_order_id,
                                    'garments_item_id' => $garments_item_id,
                                    'embl_name' => $embl_name,
                                ]);
                            $manual_total_embl_receive_query = clone $manual_embl_receive_query;

                            $total_embl_receive_qty = $manual_total_embl_receive_query->sum('production_qty');
                            $total_embl_rejection_qty = $manual_total_embl_receive_query->sum('rejection_qty');
                            $embl_receive_balance_qty = $total_embl_issue_qty - $total_embl_receive_qty;
                        }

                        $manual_sewing_input_query = ManualSewingInputProduction::query()->where([
                            'purchase_order_id' => $purchase_order_id,
                            'garments_item_id' => $garments_item_id,
                        ]);
                        $manual_total_sewing_input_query = clone $manual_sewing_input_query;

                        $total_sewing_input_qty = $manual_total_sewing_input_query->sum('production_qty');
                        $sewing_input_balance_qty = $total_cut_qty - $total_sewing_input_qty;

                        $manual_sewing_output_query = ManualHourlySewingProduction::query()->where([
                            'purchase_order_id' => $purchase_order_id,
                            'garments_item_id' => $garments_item_id,
                        ]);
                        $manual_total_sewing_output_query = clone $manual_sewing_output_query;

                        $total_sewing_output_qty = $manual_total_sewing_output_query->sum('production_qty');
                        $total_sewing_rejection_qty = $manual_total_sewing_output_query->sum('rejection_qty');
                        $total_sewing_alter_qty = $manual_total_sewing_output_query->sum('alter_qty');
                        $sewing_output_balance_qty = $total_sewing_input_qty - $total_sewing_output_qty;

                        $manual_cut_to_input_query = ManualCutToInputDelivery::query()->where([
                            'purchase_order_id' => $purchase_order_id,
                            'garments_item_id' => $garments_item_id,
                        ]);
                        $manual_total_cut_to_input_query = clone $manual_cut_to_input_query;
                        $total_cut_to_input_qty = $manual_total_cut_to_input_query->sum('production_qty');
                        $cut_to_input_balance_qty = $total_cut_qty - $total_cut_to_input_qty;

                        $manual_finishing_iron_query = ManualIronProduction::query()->where([
                            'purchase_order_id' => $purchase_order_id,
                            'garments_item_id' => $garments_item_id,
                        ]);
                        $manual_total_finishing_iron_query = clone $manual_finishing_iron_query;
                        $total_finishing_iron_qty = $manual_total_finishing_iron_query->sum('production_qty');
                        $total_finishing_iron_rejection_qty = $manual_total_finishing_iron_query->sum('rejection_qty');
                        $finishing_iron_balance_qty = $total_sewing_output_qty - $total_finishing_iron_qty;

                        $manual_poly_packing_query = ManualPolyPackingProduction::query()->where([
                            'purchase_order_id' => $purchase_order_id,
                            'garments_item_id' => $garments_item_id,
                        ]);
                        $manual_total_poly_packing_query = clone $manual_poly_packing_query;
                        $total_poly_packing_qty = $manual_total_poly_packing_query->sum('production_qty');
                        $total_poly_packing_rejection_qty = $manual_total_poly_packing_query->sum('rejection_qty');
                        $poly_packing_balance_qty = $total_sewing_output_qty - $total_poly_packing_qty;

                        $manual_inspection_query = ManualInspectionProduction::query()->where([
                            'purchase_order_id' => $purchase_order_id,
                            'garments_item_id' => $garments_item_id,
                        ]);
                        $manual_total_inspection_query = clone $manual_inspection_query;
                        $total_inspection_qty = $manual_total_inspection_query->sum('production_qty');
                        $inspection_balance_qty = $total_sewing_output_qty - $total_inspection_qty;

                        $manual_shipment_query = ManualShipmentProduction::query()->where([
                            'purchase_order_id' => $purchase_order_id,
                            'garments_item_id' => $garments_item_id,
                        ]);
                        $manual_total_shipment_query = clone $manual_shipment_query;
                        $total_shipment_qty = $manual_total_shipment_query->sum('production_qty');
                        $shipment_balance_qty = $total_poly_packing_qty - $total_shipment_qty;

                        if ($po_details) {
                            foreach ($po_details->groupBy('color_id') as $color_key => $groupByColor) {
                                $manual_color_cutting_production_query = clone $manual_cutting_production_query;
                                if ($embl_name) {
                                    $manual_color_embl_issue_query = clone $manual_embl_issue_query;
                                    $manual_color_embl_receive_query = clone $manual_embl_receive_query;
                                }
                                $manual_color_sewing_input_query = clone $manual_sewing_input_query;
                                $manual_color_cut_to_input_query = clone $manual_cut_to_input_query;
                                $manual_color_sewing_output_query = clone $manual_sewing_output_query;
                                $manual_color_finishing_iron_query = clone $manual_finishing_iron_query;
                                $manual_color_poly_packing_query = clone $manual_poly_packing_query;
                                $manual_color_shipment_query = clone $manual_shipment_query;

                                $color_wise_po_qty = 0;
                                $size_details = [];
                                foreach ($groupByColor->groupBy('size_id') as $size_key => $groupBySize) {
                                    $manual_color_size_cutting_production_query = clone $manual_cutting_production_query;
                                    if ($embl_name) {
                                        $manual_color_size_embl_issue_query = clone $manual_embl_issue_query;
                                        $manual_color_size_embl_receive_query = clone $manual_embl_receive_query;
                                    }
                                    $manual_color_size_sewing_input_query = clone $manual_sewing_input_query;
                                    $manual_color_size_cut_to_input_query = clone $manual_cut_to_input_query;
                                    $manual_color_size_sewing_output_query = clone $manual_sewing_output_query;
                                    $manual_color_size_finishing_iron_query = clone $manual_finishing_iron_query;
                                    $manual_color_size_poly_packing_query = clone $manual_poly_packing_query;
                                    $manual_color_size_shipment_query = clone $manual_shipment_query;

                                    $color_size_wise_po_qty = 0;
                                    $color_size_qty = $groupBySize->sum('quantity');
                                    $excess_cutting_percent = $groupBySize->first()->excess_cut_percent ?? 0;
                                    $color_size_wise_po_qty = ceil($color_size_qty + (($color_size_qty * $excess_cutting_percent) / 100));
                                    $color_wise_po_qty += $color_size_wise_po_qty;
                                    $po_qty += $color_size_wise_po_qty;
                                    $color_size_wise_total_cut_qty = $manual_color_size_cutting_production_query
                                        ->where('color_id', $groupByColor->first()->color_id)
                                        ->where('size_id', $groupBySize->first()->size_id)
                                        ->sum('production_qty');
                                    $color_size_wise_total_embl_issue_qty = $manual_color_size_embl_issue_query ? $manual_color_size_embl_issue_query
                                        ->where('color_id', $groupByColor->first()->color_id)
                                        ->where('size_id', $groupBySize->first()->size_id)
                                        ->sum('production_qty') : 0;
                                    $color_size_wise_total_embl_receive_qty = $manual_color_size_embl_receive_query ? $manual_color_size_embl_receive_query
                                        ->where('color_id', $groupByColor->first()->color_id)
                                        ->where('size_id', $groupBySize->first()->size_id)
                                        ->sum('production_qty') : 0;
                                    $color_size_wise_total_sewing_input_qty = $manual_color_size_sewing_input_query ? $manual_color_size_sewing_input_query
                                        ->where('color_id', $groupByColor->first()->color_id)
                                        ->where('size_id', $groupBySize->first()->size_id)
                                        ->sum('production_qty') : 0;
                                    $color_size_wise_total_cut_to_input_qty = $manual_color_size_cut_to_input_query ? $manual_color_size_cut_to_input_query
                                        ->where('color_id', $groupByColor->first()->color_id)
                                        ->where('size_id', $groupBySize->first()->size_id)
                                        ->sum('production_qty') : 0;
                                    $color_size_wise_total_sewing_output_qty = $manual_color_size_sewing_output_query ? $manual_color_size_sewing_output_query
                                        ->where('color_id', $groupByColor->first()->color_id)
                                        ->where('size_id', $groupBySize->first()->size_id)
                                        ->sum('production_qty') : 0;
                                    $color_size_wise_total_finishing_iron_qty = $manual_color_size_finishing_iron_query ? $manual_color_size_finishing_iron_query
                                        ->where('color_id', $groupByColor->first()->color_id)
                                        ->where('size_id', $groupBySize->first()->size_id)
                                        ->sum('production_qty') : 0;
                                    $color_size_wise_total_poly_packing_qty = $manual_color_size_poly_packing_query ? $manual_color_size_poly_packing_query
                                        ->where('color_id', $groupByColor->first()->color_id)
                                        ->where('size_id', $groupBySize->first()->size_id)
                                        ->sum('production_qty') : 0;
                                    $color_size_wise_total_shipment_qty = $manual_color_size_shipment_query ? $manual_color_size_shipment_query
                                        ->where('color_id', $groupByColor->first()->color_id)
                                        ->where('size_id', $groupBySize->first()->size_id)
                                        ->sum('production_qty') : 0;
                                    $color_size_wise_cutting_balance_qty = $color_size_wise_po_qty - $color_size_wise_total_cut_qty;
                                    $color_size_wise_embl_issue_balance_qty = $color_size_wise_total_cut_qty - $color_size_wise_total_embl_issue_qty;
                                    $color_size_wise_embl_receive_balance_qty = $color_size_wise_total_embl_issue_qty - $color_size_wise_total_embl_receive_qty;
                                    $color_size_wise_sewing_input_balance_qty = $color_size_wise_total_cut_qty - $color_size_wise_total_sewing_input_qty;
                                    $color_size_wise_cut_to_input_balance_qty = $color_size_wise_total_cut_qty - $color_size_wise_total_cut_to_input_qty;
                                    $color_size_wise_sewing_output_balance_qty = $color_size_wise_total_sewing_input_qty - $color_size_wise_total_sewing_output_qty;
                                    $color_size_wise_finishing_iron_balance_qty = $color_size_wise_total_sewing_output_qty - $color_size_wise_total_finishing_iron_qty;
                                    $color_size_wise_poly_packing_balance_qty = $color_size_wise_total_sewing_output_qty - $color_size_wise_total_poly_packing_qty;
                                    $color_size_wise_shipment_balance_qty = $color_size_wise_total_poly_packing_qty - $color_size_wise_total_shipment_qty;
                                    $size_details[] = [
                                        'id' => $groupBySize->first()->size_id,
                                        'text' => $groupBySize->first()->size->name,
                                        'color_size_wise_po_qty' => $color_size_wise_po_qty,
                                        'color_size_wise_total_cut_qty' => $color_size_wise_total_cut_qty,
                                        'color_size_wise_cutting_balance_qty' => $color_size_wise_cutting_balance_qty,
                                        'color_size_wise_remaining_cutting_production_qty' => $color_size_wise_cutting_balance_qty,
                                        'color_size_wise_total_embl_issue_qty' => $color_size_wise_total_embl_issue_qty ?? 0,
                                        'color_size_wise_embl_issue_balance_qty' => $color_size_wise_embl_issue_balance_qty ?? 0,
                                        'color_size_wise_remaining_embl_issue_qty' => $color_size_wise_embl_issue_balance_qty ?? 0,
                                        'color_size_wise_total_embl_receive_qty' => $color_size_wise_total_embl_receive_qty ?? 0,
                                        'color_size_wise_embl_receive_balance_qty' => $color_size_wise_embl_receive_balance_qty ?? 0,
                                        'color_size_wise_remaining_embl_receive_qty' => $color_size_wise_embl_receive_balance_qty ?? 0,
                                        'color_size_wise_total_sewing_input_qty' => $color_size_wise_total_sewing_input_qty ?? 0,
                                        'color_size_wise_sewing_input_balance_qty' => $color_size_wise_sewing_input_balance_qty ?? 0,
                                        'color_size_wise_remaining_sewing_input_qty' => $color_size_wise_sewing_input_balance_qty ?? 0,
                                        'color_size_wise_total_cut_to_input_qty' => $color_size_wise_total_cut_to_input_qty ?? 0,
                                        'color_size_wise_cut_to_input_balance_qty' => $color_size_wise_cut_to_input_balance_qty ?? 0,
                                        'color_size_wise_remaining_cut_to_input_qty' => $color_size_wise_cut_to_input_balance_qty ?? 0,
                                        'color_size_wise_total_sewing_output_qty' => $color_size_wise_total_sewing_output_qty ?? 0,
                                        'color_size_wise_sewing_output_balance_qty' => $color_size_wise_sewing_output_balance_qty ?? 0,
                                        'color_size_wise_remaining_sewing_output_qty' => $color_size_wise_sewing_output_balance_qty ?? 0,
                                        'color_size_wise_total_finishing_iron_qty' => $color_size_wise_total_finishing_iron_qty ?? 0,
                                        'color_size_wise_finishing_iron_balance_qty' => $color_size_wise_finishing_iron_balance_qty ?? 0,
                                        'color_size_wise_remaining_finishing_iron_qty' => $color_size_wise_finishing_iron_balance_qty ?? 0,
                                        'color_size_wise_total_poly_packing_qty' => $color_size_wise_total_poly_packing_qty ?? 0,
                                        'color_size_wise_poly_packing_balance_qty' => $color_size_wise_poly_packing_balance_qty ?? 0,
                                        'color_size_wise_remaining_poly_packing_qty' => $color_size_wise_poly_packing_balance_qty ?? 0,
                                        'color_size_wise_total_shipment_qty' => $color_size_wise_total_shipment_qty ?? 0,
                                        'color_size_wise_shipment_balance_qty' => $color_size_wise_shipment_balance_qty ?? 0,
                                        'color_size_wise_remaining_shipment_qty' => $color_size_wise_shipment_balance_qty ?? 0,
                                    ];
                                }
                                $color_wise_total_cut_qty = $manual_color_cutting_production_query
                                    ->where('color_id', $groupByColor->first()->color_id)
                                    ->sum('production_qty');
                                $color_wise_total_embl_issue_qty = $manual_color_embl_issue_query ? $manual_color_embl_issue_query
                                    ->where('color_id', $groupByColor->first()->color_id)
                                    ->sum('production_qty') : 0;
                                $color_wise_total_embl_receive_qty = $manual_color_embl_receive_query ? $manual_color_embl_receive_query
                                    ->where('color_id', $groupByColor->first()->color_id)
                                    ->sum('production_qty') : 0;
                                $color_wise_total_sewing_input_qty = $manual_color_sewing_input_query ? $manual_color_sewing_input_query
                                    ->where('color_id', $groupByColor->first()->color_id)
                                    ->sum('production_qty') : 0;
                                $color_wise_total_cut_to_input_qty = $manual_color_cut_to_input_query ? $manual_color_cut_to_input_query
                                    ->where('color_id', $groupByColor->first()->color_id)
                                    ->sum('production_qty') : 0;
                                $color_wise_total_sewing_output_qty = $manual_color_sewing_output_query ? $manual_color_sewing_output_query
                                    ->where('color_id', $groupByColor->first()->color_id)
                                    ->sum('production_qty') : 0;
                                $color_wise_total_finishing_iron_qty = $manual_color_finishing_iron_query ? $manual_color_finishing_iron_query
                                    ->where('color_id', $groupByColor->first()->color_id)
                                    ->sum('production_qty') : 0;
                                $color_wise_total_poly_packing_qty = $manual_color_poly_packing_query ? $manual_color_poly_packing_query
                                    ->where('color_id', $groupByColor->first()->color_id)
                                    ->sum('production_qty') : 0;
                                $color_wise_total_shipment_qty = $manual_color_shipment_query ? $manual_color_shipment_query
                                    ->where('color_id', $groupByColor->first()->color_id)
                                    ->sum('production_qty') : 0;
                                $color_wise_cutting_balance_qty = $color_wise_po_qty - $color_wise_total_cut_qty;
                                $color_wise_embl_issue_balance_qty = $color_wise_total_cut_qty - $color_wise_total_embl_issue_qty;
                                $color_wise_embl_receive_balance_qty = $color_wise_total_embl_issue_qty - $color_wise_total_embl_receive_qty;
                                $color_wise_sewing_input_balance_qty = $color_wise_total_cut_qty - $color_wise_total_sewing_input_qty;
                                $color_wise_cut_to_input_balance_qty = $color_wise_total_cut_qty - $color_wise_total_cut_to_input_qty;
                                $color_wise_sewing_output_balance_qty = $color_wise_total_sewing_input_qty - $color_wise_total_sewing_output_qty;
                                $color_wise_finishing_iron_balance_qty = $color_wise_total_sewing_output_qty - $color_wise_total_finishing_iron_qty;
                                $color_wise_poly_packing_balance_qty = $color_wise_total_sewing_output_qty - $color_wise_total_poly_packing_qty;
                                $color_wise_shipment_balance_qty = $color_wise_total_poly_packing_qty - $color_wise_total_shipment_qty;
                                $color_details[] = [
                                    'id' => $groupByColor->first()->color_id,
                                    'text' => $groupByColor->first()->color->name,
                                    'color_wise_po_qty' => $color_wise_po_qty,
                                    'color_wise_total_cut_qty' => $color_wise_total_cut_qty,
                                    'color_wise_cutting_balance_qty' => $color_wise_cutting_balance_qty,
                                    'color_wise_remaining_cutting_production_qty' => $color_wise_cutting_balance_qty,
                                    'color_wise_total_embl_issue_qty' => $color_wise_total_embl_issue_qty ?? 0,
                                    'color_wise_embl_issue_balance_qty' => $color_wise_embl_issue_balance_qty ?? 0,
                                    'color_wise_remaining_embl_issue_qty' => $color_wise_embl_issue_balance_qty ?? 0,
                                    'color_wise_total_embl_receive_qty' => $color_wise_total_embl_receive_qty ?? 0,
                                    'color_wise_embl_receive_balance_qty' => $color_wise_embl_receive_balance_qty ?? 0,
                                    'color_wise_remaining_embl_receive_qty' => $color_wise_embl_receive_balance_qty ?? 0,
                                    'color_wise_total_sewing_input_qty' => $color_wise_total_sewing_input_qty ?? 0,
                                    'color_wise_sewing_input_balance_qty' => $color_wise_sewing_input_balance_qty ?? 0,
                                    'color_wise_remaining_sewing_input_qty' => $color_wise_sewing_input_balance_qty ?? 0,
                                    'color_wise_total_cut_to_input_qty' => $color_wise_total_cut_to_input_qty ?? 0,
                                    'color_wise_cut_to_input_balance_qty' => $color_wise_cut_to_input_balance_qty ?? 0,
                                    'color_wise_remaining_cut_to_input_qty' => $color_wise_cut_to_input_balance_qty ?? 0,
                                    'color_wise_total_sewing_output_qty' => $color_wise_total_sewing_output_qty ?? 0,
                                    'color_wise_sewing_output_balance_qty' => $color_wise_sewing_output_balance_qty ?? 0,
                                    'color_wise_remaining_sewing_output_qty' => $color_wise_sewing_output_balance_qty ?? 0,
                                    'color_wise_total_finishing_iron_qty' => $color_wise_total_finishing_iron_qty ?? 0,
                                    'color_wise_finishing_iron_balance_qty' => $color_wise_finishing_iron_balance_qty ?? 0,
                                    'color_wise_remaining_finishing_iron_qty' => $color_wise_finishing_iron_balance_qty ?? 0,
                                    'color_wise_total_poly_packing_qty' => $color_wise_total_poly_packing_qty ?? 0,
                                    'color_wise_poly_packing_balance_qty' => $color_wise_poly_packing_balance_qty ?? 0,
                                    'color_wise_remaining_poly_packing_qty' => $color_wise_poly_packing_balance_qty ?? 0,
                                    'color_wise_total_shipment_qty' => $color_wise_total_shipment_qty ?? 0,
                                    'color_wise_shipment_balance_qty' => $color_wise_shipment_balance_qty ?? 0,
                                    'color_wise_remaining_shipment_qty' => $color_wise_shipment_balance_qty ?? 0,
                                    'size_details' => $size_details
                                ];
                            }
                        }
                        $cutting_balance_qty = $po_qty - $total_cut_qty;

                        return [
                            'factory_id' => $item->factory_id,
                            'factory' => $item->factory->factory_name,
                            'buyer_id' => $item->purchaseOrder->buyer_id,
                            'buyer' => $item->purchaseOrder->buyer->name,
                            'order_id' => $item->order_id,
                            'style_name' => $item->order->style_name,
                            'unique_id' => $item->order->job_no,
                            'style_and_unique_id' => ($item->order->style_name ?? '') . ($item->order->job_no ?? ''),
                            'purchase_order_id' => $item->purchase_order_id,
                            'po_no' => $item->purchaseOrder->po_no,
                            'garments_item_id' => $item->garments_item_id,
                            'garments_item' => $item->garmentItem->name,
                            'file_no' => $item->purchaseOrder->comm_file_no,
                            'internal_ref_no' => $item->purchaseOrder->internal_ref_no,
                            'country_id' => $item->purchaseOrder->country_id,
                            'country' => $item->purchaseOrder->country->name,
                            'shipment_date' => $item->purchaseOrder->country_ship_date,
                            'po_qty' => $po_qty,
                            'plan_cut_qty' => $plan_cut_qty,
                            'total_cut_qty' => $total_cut_qty,
                            'total_cutting_rejection_qty' => $total_cutting_rejection_qty,
                            'cutting_balance_qty' => $cutting_balance_qty,
                            'remaining_cutting_production_qty' => $cutting_balance_qty,
                            'total_embl_issue_qty' => $total_embl_issue_qty ?? 0,
                            'embl_issue_balance_qty' => $embl_issue_balance_qty ?? 0,
                            'remaining_embl_issue_qty' => $embl_issue_balance_qty ?? 0,
                            'total_embl_receive_qty' => $total_embl_receive_qty ?? 0,
                            'total_embl_rejection_qty' => $total_embl_rejection_qty ?? 0,
                            'embl_receive_balance_qty' => $embl_receive_balance_qty ?? 0,
                            'remaining_embl_receive_qty' => $embl_receive_balance_qty ?? 0,
                            'total_sewing_input_qty' => $total_sewing_input_qty ?? 0,
                            'sewing_input_balance_qty' => $sewing_input_balance_qty ?? 0,
                            'remaining_sewing_input_qty' => $sewing_input_balance_qty ?? 0,
                            'total_cut_to_input_qty' => $total_cut_to_input_qty ?? 0,
                            'cut_to_input_balance_qty' => $cut_to_input_balance_qty ?? 0,
                            'remaining_cut_to_input_qty' => $cut_to_input_balance_qty ?? 0,
                            'total_sewing_output_qty' => $total_sewing_output_qty ?? 0,
                            'total_sewing_rejection_qty' => $total_sewing_rejection_qty ?? 0,
                            'total_sewing_alter_qty' => $total_sewing_alter_qty ?? 0,
                            'sewing_output_balance_qty' => $sewing_output_balance_qty ?? 0,
                            'remaining_sewing_output_qty' => $sewing_output_balance_qty ?? 0,
                            'total_finishing_iron_qty' => $total_finishing_iron_qty ?? 0,
                            'total_finishing_iron_rejection_qty' => $total_finishing_iron_rejection_qty ?? 0,
                            'finishing_iron_balance_qty' => $finishing_iron_balance_qty ?? 0,
                            'remaining_finishing_iron_qty' => $finishing_iron_balance_qty ?? 0,
                            'total_poly_packing_qty' => $total_poly_packing_qty ?? 0,
                            'total_poly_packing_rejection_qty' => $total_poly_packing_rejection_qty ?? 0,
                            'poly_packing_balance_qty' => $poly_packing_balance_qty ?? 0,
                            'remaining_poly_packing_qty' => $poly_packing_balance_qty ?? 0,
                            'total_inspection_qty' => $total_inspection_qty ?? 0,
                            'inspection_balance_qty' => $inspection_balance_qty ?? 0,
                            'remaining_inspection_qty' => $inspection_balance_qty ?? 0,
                            'total_shipment_qty' => $total_shipment_qty ?? 0,
                            'shipment_balance_qty' => $shipment_balance_qty ?? 0,
                            'remaining_shipment_qty' => $shipment_balance_qty ?? 0,
                            'color_details' => $color_details,
                        ];
                    });
            }

            return [
                'data' => $data,
                'error' => null,
                'message' => 'Success',
                'status' => 200,
            ];
        } catch (Exception $e) {
            return [
                'data' => [],
                'error' => $e->getMessage(),
                'message' => 'Something went wrong',
                'status' => 500,
            ];
        }
    }

    public static function getSingleSearchData($purchase_order_id, $garments_item_id, $embl_name = ''): array
    {
        $po_qty = 0;
        $color_details = [];
        $po_details = PurchaseOrderDetail::query()
            ->where([
                'purchase_order_id' => $purchase_order_id,
                'garments_item_id' => $garments_item_id,
            ])
            ->where('quantity', '>', 0)
            ->get();

        $manual_cutting_production_query = ManualCuttingProduction::query()
            ->where([
                'purchase_order_id' => $purchase_order_id,
                'garments_item_id' => $garments_item_id,
            ]);
        $manual_total_cutting_production_query = clone $manual_cutting_production_query;

        $total_cut_qty = $manual_total_cutting_production_query->sum('production_qty');
        $total_cutting_rejection_qty = $manual_total_cutting_production_query->sum('rejection_qty');

        $manual_color_embl_issue_query = null;
        $manual_color_size_embl_issue_query = null;
        $manual_color_embl_receive_query = null;
        $manual_color_size_embl_receive_query = null;
        if ($embl_name) {
            $manual_embl_issue_query = ManualEmblIssueProduction::query()
                ->where([
                    'purchase_order_id' => $purchase_order_id,
                    'garments_item_id' => $garments_item_id,
                    'embl_name' => $embl_name,
                ]);
            $manual_total_embl_issue_query = clone $manual_embl_issue_query;

            $total_embl_issue_qty = $manual_total_embl_issue_query->sum('production_qty');
            $embl_issue_balance_qty = $total_cut_qty - $total_embl_issue_qty;

            $manual_embl_receive_query = ManualEmblReceiveProduction::query()
                ->where([
                    'purchase_order_id' => $purchase_order_id,
                    'garments_item_id' => $garments_item_id,
                    'embl_name' => $embl_name,
                ]);
            $manual_total_embl_receive_query = clone $manual_embl_receive_query;

            $total_embl_receive_qty = $manual_total_embl_receive_query->sum('production_qty');
            $total_embl_rejection_qty = $manual_total_embl_receive_query->sum('rejection_qty');
            $embl_receive_balance_qty = $total_embl_issue_qty - $total_embl_receive_qty;
        }

        $manual_sewing_input_query = ManualSewingInputProduction::query()->where([
            'purchase_order_id' => $purchase_order_id,
            'garments_item_id' => $garments_item_id,
        ]);
        $manual_total_sewing_input_query = clone $manual_sewing_input_query;

        $total_sewing_input_qty = $manual_total_sewing_input_query->sum('production_qty');
        $sewing_input_balance_qty = $total_cut_qty - $total_sewing_input_qty;

        $manual_sewing_output_query = ManualHourlySewingProduction::query()->where([
            'purchase_order_id' => $purchase_order_id,
            'garments_item_id' => $garments_item_id,
        ]);
        $manual_total_sewing_output_query = clone $manual_sewing_output_query;

        $total_sewing_output_qty = $manual_total_sewing_output_query->sum('production_qty');
        $total_sewing_rejection_qty = $manual_total_sewing_output_query->sum('rejection_qty');
        $total_sewing_alter_qty = $manual_total_sewing_output_query->sum('alter_qty');
        $sewing_output_balance_qty = $total_sewing_input_qty - $total_sewing_output_qty;

        $manual_cut_to_input_query = ManualCutToInputDelivery::query()->where([
            'purchase_order_id' => $purchase_order_id,
            'garments_item_id' => $garments_item_id,
        ]);
        $manual_total_cut_to_input_query = clone $manual_cut_to_input_query;
        $total_cut_to_input_qty = $manual_total_cut_to_input_query->sum('production_qty');
        $cut_to_input_balance_qty = $total_cut_qty - $total_cut_to_input_qty;

        $manual_finishing_iron_query = ManualIronProduction::query()->where([
            'purchase_order_id' => $purchase_order_id,
            'garments_item_id' => $garments_item_id,
        ]);
        $manual_total_finishing_iron_query = clone $manual_finishing_iron_query;
        $total_finishing_iron_qty = $manual_total_finishing_iron_query->sum('production_qty');
        $total_finishing_iron_rejection_qty = $manual_total_finishing_iron_query->sum('rejection_qty');
        $finishing_iron_balance_qty = $total_sewing_output_qty - $total_finishing_iron_qty;

        $manual_poly_packing_query = ManualPolyPackingProduction::query()->where([
            'purchase_order_id' => $purchase_order_id,
            'garments_item_id' => $garments_item_id,
        ]);
        $manual_total_poly_packing_query = clone $manual_poly_packing_query;
        $total_poly_packing_qty = $manual_total_poly_packing_query->sum('production_qty');
        $total_poly_packing_rejection_qty = $manual_total_poly_packing_query->sum('rejection_qty');
        $poly_packing_balance_qty = $total_sewing_output_qty - $total_poly_packing_qty;

        $manual_inspection_query = ManualInspectionProduction::query()->where([
            'purchase_order_id' => $purchase_order_id,
            'garments_item_id' => $garments_item_id,
        ]);
        $manual_total_inspection_query = clone $manual_inspection_query;
        $total_inspection_qty = $manual_total_inspection_query->sum('production_qty');
        $inspection_balance_qty = $total_sewing_output_qty - $total_inspection_qty;

        $manual_shipment_query = ManualShipmentProduction::query()->where([
            'purchase_order_id' => $purchase_order_id,
            'garments_item_id' => $garments_item_id,
        ]);
        $manual_total_shipment_query = clone $manual_shipment_query;
        $total_shipment_qty = $manual_total_shipment_query->sum('production_qty');
        $shipment_balance_qty = $total_poly_packing_qty - $total_shipment_qty;

        if ($po_details) {
            foreach ($po_details->groupBy('color_id') as $color_key => $groupByColor) {
                $manual_color_cutting_production_query = clone $manual_cutting_production_query;
                if ($embl_name) {
                    $manual_color_embl_issue_query = clone $manual_embl_issue_query;
                    $manual_color_embl_receive_query = clone $manual_embl_receive_query;
                }
                $manual_color_sewing_input_query = clone $manual_sewing_input_query;
                $manual_color_cut_to_input_query = clone $manual_cut_to_input_query;
                $manual_color_sewing_output_query = clone $manual_sewing_output_query;
                $manual_color_finishing_iron_query = clone $manual_finishing_iron_query;
                $manual_color_poly_packing_query = clone $manual_poly_packing_query;
                $manual_color_shipment_query = clone $manual_shipment_query;

                $color_wise_po_qty = 0;
                $size_details = [];
                foreach ($groupByColor->groupBy('size_id') as $size_key => $groupBySize) {
                    $manual_color_size_cutting_production_query = clone $manual_cutting_production_query;
                    if ($embl_name) {
                        $manual_color_size_embl_issue_query = clone $manual_embl_issue_query;
                        $manual_color_size_embl_receive_query = clone $manual_embl_receive_query;
                    }
                    $manual_color_size_sewing_input_query = clone $manual_sewing_input_query;
                    $manual_color_size_cut_to_input_query = clone $manual_cut_to_input_query;
                    $manual_color_size_sewing_output_query = clone $manual_sewing_output_query;
                    $manual_color_size_finishing_iron_query = clone $manual_finishing_iron_query;
                    $manual_color_size_poly_packing_query = clone $manual_poly_packing_query;
                    $manual_color_size_shipment_query = clone $manual_shipment_query;

                    $color_size_wise_po_qty = 0;
                    $color_size_qty = $groupBySize->sum('quantity');
                    $excess_cutting_percent = $groupBySize->first()->excess_cut_percent ?? 0;
                    $color_size_wise_po_qty = ceil($color_size_qty + (($color_size_qty * $excess_cutting_percent) / 100));
                    $color_wise_po_qty += $color_size_wise_po_qty;
                    $po_qty += $color_size_wise_po_qty;
                    $color_size_wise_total_cut_qty = $manual_color_size_cutting_production_query
                        ->where('color_id', $groupByColor->first()->color_id)
                        ->where('size_id', $groupBySize->first()->size_id)
                        ->sum('production_qty');
                    $color_size_wise_total_embl_issue_qty = $manual_color_size_embl_issue_query ? $manual_color_size_embl_issue_query
                        ->where('color_id', $groupByColor->first()->color_id)
                        ->where('size_id', $groupBySize->first()->size_id)
                        ->sum('production_qty') : 0;
                    $color_size_wise_total_embl_receive_qty = $manual_color_size_embl_receive_query ? $manual_color_size_embl_receive_query
                        ->where('color_id', $groupByColor->first()->color_id)
                        ->where('size_id', $groupBySize->first()->size_id)
                        ->sum('production_qty') : 0;
                    $color_size_wise_total_sewing_input_qty = $manual_color_size_sewing_input_query ? $manual_color_size_sewing_input_query
                        ->where('color_id', $groupByColor->first()->color_id)
                        ->where('size_id', $groupBySize->first()->size_id)
                        ->sum('production_qty') : 0;
                    $color_size_wise_total_cut_to_input_qty = $manual_color_size_cut_to_input_query ? $manual_color_size_cut_to_input_query
                        ->where('color_id', $groupByColor->first()->color_id)
                        ->where('size_id', $groupBySize->first()->size_id)
                        ->sum('production_qty') : 0;
                    $color_size_wise_total_sewing_output_qty = $manual_color_size_sewing_output_query ? $manual_color_size_sewing_output_query
                        ->where('color_id', $groupByColor->first()->color_id)
                        ->where('size_id', $groupBySize->first()->size_id)
                        ->sum('production_qty') : 0;
                    $color_size_wise_total_finishing_iron_qty = $manual_color_size_finishing_iron_query ? $manual_color_size_finishing_iron_query
                        ->where('color_id', $groupByColor->first()->color_id)
                        ->where('size_id', $groupBySize->first()->size_id)
                        ->sum('production_qty') : 0;
                    $color_size_wise_total_poly_packing_qty = $manual_color_size_poly_packing_query ? $manual_color_size_poly_packing_query
                        ->where('color_id', $groupByColor->first()->color_id)
                        ->where('size_id', $groupBySize->first()->size_id)
                        ->sum('production_qty') : 0;
                    $color_size_wise_total_shipment_qty = $manual_color_size_shipment_query ? $manual_color_size_shipment_query
                        ->where('color_id', $groupByColor->first()->color_id)
                        ->where('size_id', $groupBySize->first()->size_id)
                        ->sum('production_qty') : 0;
                    $color_size_wise_cutting_balance_qty = $color_size_wise_po_qty - $color_size_wise_total_cut_qty;
                    $color_size_wise_embl_issue_balance_qty = $color_size_wise_total_cut_qty - $color_size_wise_total_embl_issue_qty;
                    $color_size_wise_embl_receive_balance_qty = $color_size_wise_total_embl_issue_qty - $color_size_wise_total_embl_receive_qty;
                    $color_size_wise_sewing_input_balance_qty = $color_size_wise_total_cut_qty - $color_size_wise_total_sewing_input_qty;
                    $color_size_wise_cut_to_input_balance_qty = $color_size_wise_total_cut_qty - $color_size_wise_total_cut_to_input_qty;
                    $color_size_wise_sewing_output_balance_qty = $color_size_wise_total_sewing_input_qty - $color_size_wise_total_sewing_output_qty;
                    $color_size_wise_finishing_iron_balance_qty = $color_size_wise_total_sewing_output_qty - $color_size_wise_total_finishing_iron_qty;
                    $color_size_wise_poly_packing_balance_qty = $color_size_wise_total_sewing_output_qty - $color_size_wise_total_poly_packing_qty;
                    $color_size_wise_shipment_balance_qty = $color_size_wise_total_poly_packing_qty - $color_size_wise_total_shipment_qty;
                    $size_details[] = [
                        'id' => $groupBySize->first()->size_id,
                        'text' => $groupBySize->first()->size->name,
                        'color_size_wise_po_qty' => $color_size_wise_po_qty,
                        'color_size_wise_total_cut_qty' => $color_size_wise_total_cut_qty,
                        'color_size_wise_cutting_balance_qty' => $color_size_wise_cutting_balance_qty,
                        'color_size_wise_remaining_cutting_production_qty' => $color_size_wise_cutting_balance_qty,
                        'color_size_wise_total_embl_issue_qty' => $color_size_wise_total_embl_issue_qty ?? 0,
                        'color_size_wise_embl_issue_balance_qty' => $color_size_wise_embl_issue_balance_qty ?? 0,
                        'color_size_wise_remaining_embl_issue_qty' => $color_size_wise_embl_issue_balance_qty ?? 0,
                        'color_size_wise_total_embl_receive_qty' => $color_size_wise_total_embl_receive_qty ?? 0,
                        'color_size_wise_embl_receive_balance_qty' => $color_size_wise_embl_receive_balance_qty ?? 0,
                        'color_size_wise_remaining_embl_receive_qty' => $color_size_wise_embl_receive_balance_qty ?? 0,
                        'color_size_wise_total_sewing_input_qty' => $color_size_wise_total_sewing_input_qty ?? 0,
                        'color_size_wise_sewing_input_balance_qty' => $color_size_wise_sewing_input_balance_qty ?? 0,
                        'color_size_wise_remaining_sewing_input_qty' => $color_size_wise_sewing_input_balance_qty ?? 0,
                        'color_size_wise_total_cut_to_input_qty' => $color_size_wise_total_cut_to_input_qty ?? 0,
                        'color_size_wise_cut_to_input_balance_qty' => $color_size_wise_cut_to_input_balance_qty ?? 0,
                        'color_size_wise_remaining_cut_to_input_qty' => $color_size_wise_cut_to_input_balance_qty ?? 0,
                        'color_size_wise_total_sewing_output_qty' => $color_size_wise_total_sewing_output_qty ?? 0,
                        'color_size_wise_sewing_output_balance_qty' => $color_size_wise_sewing_output_balance_qty ?? 0,
                        'color_size_wise_remaining_sewing_output_qty' => $color_size_wise_sewing_output_balance_qty ?? 0,
                        'color_size_wise_total_finishing_iron_qty' => $color_size_wise_total_finishing_iron_qty ?? 0,
                        'color_size_wise_finishing_iron_balance_qty' => $color_size_wise_finishing_iron_balance_qty ?? 0,
                        'color_size_wise_remaining_finishing_iron_qty' => $color_size_wise_finishing_iron_balance_qty ?? 0,
                        'color_size_wise_total_poly_packing_qty' => $color_size_wise_total_poly_packing_qty ?? 0,
                        'color_size_wise_poly_packing_balance_qty' => $color_size_wise_poly_packing_balance_qty ?? 0,
                        'color_size_wise_remaining_poly_packing_qty' => $color_size_wise_poly_packing_balance_qty ?? 0,
                        'color_size_wise_total_shipment_qty' => $color_size_wise_total_shipment_qty ?? 0,
                        'color_size_wise_shipment_balance_qty' => $color_size_wise_shipment_balance_qty ?? 0,
                        'color_size_wise_remaining_shipment_qty' => $color_size_wise_shipment_balance_qty ?? 0,
                    ];
                }
                $color_wise_total_cut_qty = $manual_color_cutting_production_query
                    ->where('color_id', $groupByColor->first()->color_id)
                    ->sum('production_qty');
                $color_wise_total_embl_issue_qty = $manual_color_embl_issue_query ? $manual_color_embl_issue_query
                    ->where('color_id', $groupByColor->first()->color_id)
                    ->sum('production_qty') : 0;
                $color_wise_total_embl_receive_qty = $manual_color_embl_receive_query ? $manual_color_embl_receive_query
                    ->where('color_id', $groupByColor->first()->color_id)
                    ->sum('production_qty') : 0;
                $color_wise_total_sewing_input_qty = $manual_color_sewing_input_query ? $manual_color_sewing_input_query
                    ->where('color_id', $groupByColor->first()->color_id)
                    ->sum('production_qty') : 0;
                $color_wise_total_cut_to_input_qty = $manual_color_cut_to_input_query ? $manual_color_cut_to_input_query
                    ->where('color_id', $groupByColor->first()->color_id)
                    ->sum('production_qty') : 0;
                $color_wise_total_sewing_output_qty = $manual_color_sewing_output_query ? $manual_color_sewing_output_query
                    ->where('color_id', $groupByColor->first()->color_id)
                    ->sum('production_qty') : 0;
                $color_wise_total_finishing_iron_qty = $manual_color_finishing_iron_query ? $manual_color_finishing_iron_query
                    ->where('color_id', $groupByColor->first()->color_id)
                    ->sum('production_qty') : 0;
                $color_wise_total_poly_packing_qty = $manual_color_poly_packing_query ? $manual_color_poly_packing_query
                    ->where('color_id', $groupByColor->first()->color_id)
                    ->sum('production_qty') : 0;
                $color_wise_total_shipment_qty = $manual_color_shipment_query ? $manual_color_shipment_query
                    ->where('color_id', $groupByColor->first()->color_id)
                    ->sum('production_qty') : 0;
                $color_wise_cutting_balance_qty = $color_wise_po_qty - $color_wise_total_cut_qty;
                $color_wise_embl_issue_balance_qty = $color_wise_total_cut_qty - $color_wise_total_embl_issue_qty;
                $color_wise_embl_receive_balance_qty = $color_wise_total_embl_issue_qty - $color_wise_total_embl_receive_qty;
                $color_wise_sewing_input_balance_qty = $color_wise_total_cut_qty - $color_wise_total_sewing_input_qty;
                $color_wise_cut_to_input_balance_qty = $color_wise_total_cut_qty - $color_wise_total_cut_to_input_qty;
                $color_wise_sewing_output_balance_qty = $color_wise_total_sewing_input_qty - $color_wise_total_sewing_output_qty;
                $color_wise_finishing_iron_balance_qty = $color_wise_total_sewing_output_qty - $color_wise_total_finishing_iron_qty;
                $color_wise_poly_packing_balance_qty = $color_wise_total_sewing_output_qty - $color_wise_total_poly_packing_qty;
                $color_wise_shipment_balance_qty = $color_wise_total_poly_packing_qty - $color_wise_total_shipment_qty;
                $color_details[] = [
                    'id' => $groupByColor->first()->color_id,
                    'text' => $groupByColor->first()->color->name,
                    'color_wise_po_qty' => $color_wise_po_qty,
                    'color_wise_total_cut_qty' => $color_wise_total_cut_qty,
                    'color_wise_cutting_balance_qty' => $color_wise_cutting_balance_qty,
                    'color_wise_remaining_cutting_production_qty' => $color_wise_cutting_balance_qty,
                    'color_wise_total_embl_issue_qty' => $color_wise_total_embl_issue_qty ?? 0,
                    'color_wise_embl_issue_balance_qty' => $color_wise_embl_issue_balance_qty ?? 0,
                    'color_wise_remaining_embl_issue_qty' => $color_wise_embl_issue_balance_qty ?? 0,
                    'color_wise_total_embl_receive_qty' => $color_wise_total_embl_receive_qty ?? 0,
                    'color_wise_embl_receive_balance_qty' => $color_wise_embl_receive_balance_qty ?? 0,
                    'color_wise_remaining_embl_receive_qty' => $color_wise_embl_receive_balance_qty ?? 0,
                    'color_wise_total_sewing_input_qty' => $color_wise_total_sewing_input_qty ?? 0,
                    'color_wise_sewing_input_balance_qty' => $color_wise_sewing_input_balance_qty ?? 0,
                    'color_wise_remaining_sewing_input_qty' => $color_wise_sewing_input_balance_qty ?? 0,
                    'color_wise_total_cut_to_input_qty' => $color_wise_total_cut_to_input_qty ?? 0,
                    'color_wise_cut_to_input_balance_qty' => $color_wise_cut_to_input_balance_qty ?? 0,
                    'color_wise_remaining_cut_to_input_qty' => $color_wise_cut_to_input_balance_qty ?? 0,
                    'color_wise_total_sewing_output_qty' => $color_wise_total_sewing_output_qty ?? 0,
                    'color_wise_sewing_output_balance_qty' => $color_wise_sewing_output_balance_qty ?? 0,
                    'color_wise_remaining_sewing_output_qty' => $color_wise_sewing_output_balance_qty ?? 0,
                    'color_wise_total_finishing_iron_qty' => $color_wise_total_finishing_iron_qty ?? 0,
                    'color_wise_finishing_iron_balance_qty' => $color_wise_finishing_iron_balance_qty ?? 0,
                    'color_wise_remaining_finishing_iron_qty' => $color_wise_finishing_iron_balance_qty ?? 0,
                    'color_wise_total_poly_packing_qty' => $color_wise_total_poly_packing_qty ?? 0,
                    'color_wise_poly_packing_balance_qty' => $color_wise_poly_packing_balance_qty ?? 0,
                    'color_wise_remaining_poly_packing_qty' => $color_wise_poly_packing_balance_qty ?? 0,
                    'color_wise_total_shipment_qty' => $color_wise_total_shipment_qty ?? 0,
                    'color_wise_shipment_balance_qty' => $color_wise_shipment_balance_qty ?? 0,
                    'color_wise_remaining_shipment_qty' => $color_wise_shipment_balance_qty ?? 0,
                    'size_details' => $size_details
                ];
            }
        }
        $cutting_balance_qty = $po_qty - $total_cut_qty;

        return [
            'po_qty' => $po_qty,
            'total_cut_qty' => $total_cut_qty,
            'total_cutting_rejection_qty' => $total_cutting_rejection_qty,
            'cutting_balance_qty' => $cutting_balance_qty,
            'remaining_cutting_production_qty' => $cutting_balance_qty,
            'total_embl_issue_qty' => $total_embl_issue_qty ?? 0,
            'embl_issue_balance_qty' => $embl_issue_balance_qty ?? 0,
            'remaining_embl_issue_qty' => $embl_issue_balance_qty ?? 0,
            'total_embl_receive_qty' => $total_embl_receive_qty ?? 0,
            'total_embl_rejection_qty' => $total_embl_rejection_qty ?? 0,
            'embl_receive_balance_qty' => $embl_receive_balance_qty ?? 0,
            'remaining_embl_receive_qty' => $embl_receive_balance_qty ?? 0,
            'total_sewing_input_qty' => $total_sewing_input_qty ?? 0,
            'sewing_input_balance_qty' => $sewing_input_balance_qty ?? 0,
            'remaining_sewing_input_qty' => $sewing_input_balance_qty ?? 0,
            'total_cut_to_input_qty' => $total_cut_to_input_qty ?? 0,
            'cut_to_input_balance_qty' => $cut_to_input_balance_qty ?? 0,
            'remaining_cut_to_input_qty' => $cut_to_input_balance_qty ?? 0,
            'total_sewing_output_qty' => $total_sewing_output_qty ?? 0,
            'total_sewing_rejection_qty' => $total_sewing_rejection_qty ?? 0,
            'total_sewing_alter_qty' => $total_sewing_alter_qty ?? 0,
            'sewing_output_balance_qty' => $sewing_output_balance_qty ?? 0,
            'remaining_sewing_output_qty' => $sewing_output_balance_qty ?? 0,
            'total_finishing_iron_qty' => $total_finishing_iron_qty ?? 0,
            'total_finishing_iron_rejection_qty' => $total_finishing_iron_rejection_qty ?? 0,
            'finishing_iron_balance_qty' => $finishing_iron_balance_qty ?? 0,
            'remaining_finishing_iron_qty' => $finishing_iron_balance_qty ?? 0,
            'total_poly_packing_qty' => $total_poly_packing_qty ?? 0,
            'total_poly_packing_rejection_qty' => $total_poly_packing_rejection_qty ?? 0,
            'poly_packing_balance_qty' => $poly_packing_balance_qty ?? 0,
            'remaining_poly_packing_qty' => $poly_packing_balance_qty ?? 0,
            'total_inspection_qty' => $total_inspection_qty ?? 0,
            'inspection_balance_qty' => $inspection_balance_qty ?? 0,
            'remaining_inspection_qty' => $inspection_balance_qty ?? 0,
            'total_shipment_qty' => $total_shipment_qty ?? 0,
            'shipment_balance_qty' => $shipment_balance_qty ?? 0,
            'remaining_shipment_qty' => $shipment_balance_qty ?? 0,
            'color_details' => $color_details,
        ];
    }

    public function searchSubcontractFactories(Request $request): JsonResponse
    {
        try {
            $data = [];
            $operation_type = $request->operation_type ?? null;
            $search_query = $request->search_query ?? null;
            $factory_id = $request->factory_id ?? null;
            $data = SubcontractFactoryProfile::query()
                ->withoutGlobalScope('factoryId')
                ->where('status', 1)
                ->when($factory_id, function ($query) use ($factory_id) {
                    $query->where('factory_id', $factory_id);
                })
                ->when(!$factory_id, function ($query) {
                    $query->where('factory_id', factoryId());
                })
                ->when($operation_type, function ($query) use ($operation_type) {
                    $query->where('operation_type', $operation_type);
                })
                ->when($search_query, function ($query) use ($search_query) {
                    $query->where('name', 'LIKE', $search_query . '%');
                })
                ->limit(20)
                ->get()
                ->map(function ($item, $key) {
                    $factory = $item->factory->factory_name ?? '';
                    $data['id'] = $item->id;
                    $data['text'] = $item->name . '[' . $factory . ']';
                    return $data;
                });
            return response()->json([
                'data' => $data,
                'error' => null,
                'message' => 'Success',
                'status' => 200,
            ]);
        } catch (Exception $e) {
            return response()->json([
                'data' => [],
                'error' => $e->getMessage(),
                'message' => 'Something went wrong',
                'status' => 500,
            ]);
        }
    }

    public function searchCuttingFloors(Request $request): JsonResponse
    {
        try {
            $data = [];
            $sub_factory_id = $request->sub_factory_id ?? null;
            $search_query = $request->search_query ?? null;
            $factory_id = $request->factory_id ?? null;
            if (!$sub_factory_id) {
                $data = CuttingFloor::query()
                    ->withoutGlobalScope('factoryId')
                    ->when($factory_id, function ($query) use ($factory_id) {
                        $query->where('factory_id', $factory_id);
                    })
                    ->when(!$factory_id, function ($query) use ($factory_id) {
                        $query->where('factory_id', factoryId());
                    })
                    ->when($search_query, function ($query) use ($search_query) {
                        $query->where('floor_no', 'LIKE', $search_query . '%');
                    })
                    ->limit(20)
                    ->get()
                    ->map(function ($item, $key) {
                        $factory = $item->factory->factory_name ?? '';
                        $data['id'] = $item->id;
                        $data['text'] = $item->floor_no . '[' . $factory . ']';
                        return $data;
                    });
            } else {
                $data = SubcontractCuttingFloor::query()
                    ->withoutGlobalScope('factoryId')
                    ->where('status', 1)
                    ->when($factory_id, function ($query) use ($factory_id) {
                        $query->where('factory_id', $factory_id);
                    })
                    ->when(!$factory_id, function ($query) use ($factory_id) {
                        $query->where('factory_id', factoryId());
                    })
                    ->where('subcontract_factory_profile_id', $sub_factory_id)
                    ->when($search_query, function ($query) use ($search_query) {
                        $query->where('floor_name', 'LIKE', $search_query . '%');
                    })
                    ->limit(20)
                    ->get()
                    ->map(function ($item, $key) {
                        $factory = $item->factory->factory_name ?? '';
                        $data['id'] = $item->id;
                        $data['text'] = $item->floor_name . '[' . $factory . ']';
                        return $data;
                    });
            }
            return response()->json([
                'data' => $data,
                'error' => null,
                'message' => 'Success',
                'status' => 200,
            ]);
        } catch (Exception $e) {
            return response()->json([
                'data' => [],
                'error' => $e->getMessage(),
                'message' => 'Something went wrong',
                'status' => 500,
            ]);
        }
    }

    public function searchCuttingTables(Request $request): JsonResponse
    {
        try {
            $data = [];
            $cutting_floor_id = $request->cutting_floor_id ?? null;
            $sub_factory_id = $request->sub_factory_id ?? null;
            $search_query = $request->search_query ?? null;
            if (!$cutting_floor_id) {
                return response()->json([
                    'data' => $data,
                    'error' => null,
                    'message' => 'Data not found',
                    'status' => 200,
                ]);
            }
            if (!$sub_factory_id) {
                $data = CuttingTable::query()
                    ->withoutGlobalScope('factoryId')
                    ->when($cutting_floor_id, function ($query) use ($cutting_floor_id) {
                        $query->where('cutting_floor_id', $cutting_floor_id);
                    })
                    ->when($search_query, function ($query) use ($search_query) {
                        $query->where('table_no', 'LIKE', $search_query . '%');
                    })
                    ->limit(20)
                    ->get()
                    ->map(function ($item, $key) {
                        $factory = $item->factory->factory_name ?? '';
                        $data['id'] = $item->id;
                        $data['text'] = $item->table_no . '[' . $factory . ']';
                        return $data;
                    });
            } else {
                $data = SubcontractCuttingTable::query()
                    ->withoutGlobalScope('factoryId')
                    ->where('status', 1)
                    ->where('subcontract_factory_profile_id', $sub_factory_id)
                    ->when($cutting_floor_id, function ($query) use ($cutting_floor_id) {
                        $query->where('subcontract_cutting_floor_id', $cutting_floor_id);
                    })
                    ->when($search_query, function ($query) use ($search_query) {
                        $query->where('table_name', 'LIKE', $search_query . '%');
                    })
                    ->limit(20)
                    ->get()
                    ->map(function ($item, $key) {
                        $factory = $item->factory->factory_name ?? '';
                        $data['id'] = $item->id;
                        $data['text'] = $item->table_name . '[' . $factory . ']';
                        return $data;
                    });
            }
            return response()->json([
                'data' => $data,
                'error' => null,
                'message' => 'Success',
                'status' => 200,
            ]);
        } catch (Exception $e) {
            return response()->json([
                'data' => [],
                'error' => $e->getMessage(),
                'message' => 'Something went wrong',
                'status' => 500,
            ]);
        }
    }

    public function searchSewingFloors(Request $request): JsonResponse
    {
        try {
            $data = [];
            $sub_factory_id = $request->sub_factory_id ?? null;
            $search_query = $request->search_query ?? null;
            $factory_id = $request->factory_id ?? null;
            if (!$sub_factory_id) {
                $data = Floor::query()
                    ->withoutGlobalScope('factoryId')
                    ->when($factory_id, function ($query) use ($factory_id) {
                        $query->where('factory_id', $factory_id);
                    })
                    ->when(!$factory_id, function ($query) use ($factory_id) {
                        $query->where('factory_id', factoryId());
                    })
                    ->when($search_query, function ($query) use ($search_query) {
                        $query->where('floor_no', 'LIKE', $search_query . '%');
                    })
                    ->limit(20)
                    ->get()
                    ->map(function ($item, $key) {
                        $factory = $item->factory->factory_name ?? '';
                        $data['id'] = $item->id;
                        $data['text'] = $item->floor_no . '[' . $factory . ']';
                        return $data;
                    });
            } else {
                $data = SubcontractSewingFloor::query()
                    ->withoutGlobalScope('factoryId')
                    ->where('status', 1)
                    ->when($factory_id, function ($query) use ($factory_id) {
                        $query->where('factory_id', $factory_id);
                    })
                    ->when(!$factory_id, function ($query) use ($factory_id) {
                        $query->where('factory_id', factoryId());
                    })
                    ->where('subcontract_factory_profile_id', $sub_factory_id)
                    ->when($search_query, function ($query) use ($search_query) {
                        $query->where('floor_name', 'LIKE', $search_query . '%');
                    })
                    ->limit(20)
                    ->get()
                    ->map(function ($item, $key) {
                        $factory = $item->factory->factory_name ?? '';
                        $data['id'] = $item->id;
                        $data['text'] = $item->floor_name . '[' . $factory . ']';
                        return $data;
                    });
            }
            return response()->json([
                'data' => $data,
                'error' => null,
                'message' => 'Success',
                'status' => 200,
            ]);
        } catch (Exception $e) {
            return response()->json([
                'data' => [],
                'error' => $e->getMessage(),
                'message' => 'Something went wrong',
                'status' => 500,
            ]);
        }
    }

    public function searchSewingLines(Request $request): JsonResponse
    {
        try {
            $data = [];
            $floor_id = $request->floor_id ?? null;
            $sub_factory_id = $request->sub_factory_id ?? null;
            $search_query = $request->search_query ?? null;
            if (!$floor_id) {
                return response()->json([
                    'data' => $data,
                    'error' => null,
                    'message' => 'Data not found',
                    'status' => 200,
                ]);
            }
            if (!$sub_factory_id) {
                $data = Line::query()
                    ->withoutGlobalScope('factoryId')
                    ->when($floor_id, function ($query) use ($floor_id) {
                        $query->where('floor_id', $floor_id);
                    })
                    ->when($search_query, function ($query) use ($search_query) {
                        $query->where('line_no', 'LIKE', $search_query . '%');
                    })
                    ->limit(20)
                    ->get()
                    ->map(function ($item, $key) {
                        $factory = $item->factory->factory_name ?? '';
                        $data['id'] = $item->id;
                        $data['text'] = $item->line_no . '[' . $factory . ']';
                        return $data;
                    });
            } else {
                $data = SubcontractSewingLine::query()
                    ->withoutGlobalScope('factoryId')
                    ->where('status', 1)
                    ->where('subcontract_factory_profile_id', $sub_factory_id)
                    ->when($floor_id, function ($query) use ($floor_id) {
                        $query->where('subcontract_sewing_floor_id', $floor_id);
                    })
                    ->when($search_query, function ($query) use ($search_query) {
                        $query->where('line_name', 'LIKE', $search_query . '%');
                    })
                    ->limit(20)
                    ->get()
                    ->map(function ($item, $key) {
                        $factory = $item->factory->factory_name ?? '';
                        $data['id'] = $item->id;
                        $data['text'] = $item->line_name . '[' . $factory . ']';
                        return $data;
                    });
            }
            return response()->json([
                'data' => $data,
                'error' => null,
                'message' => 'Success',
                'status' => 200,
            ]);
        } catch (Exception $e) {
            return response()->json([
                'data' => [],
                'error' => $e->getMessage(),
                'message' => 'Something went wrong',
                'status' => 500,
            ]);
        }
    }

    public function searchEmbellishmentFloors(Request $request): JsonResponse
    {
        try {
            $data = [];
            $sub_factory_id = $request->sub_factory_id ?? null;
            $search_query = $request->search_query ?? null;
            $factory_id = $request->factory_id ?? null;
            if (!$sub_factory_id) {
                $data = [];
            } else {
                $data = SubcontractEmbellishmentFloor::query()
                    ->withoutGlobalScope('factoryId')
                    ->where('status', 1)
                    ->when($factory_id, function ($query) use ($factory_id) {
                        $query->where('factory_id', $factory_id);
                    })
                    ->when($sub_factory_id, function ($query) use ($sub_factory_id) {
                        $query->where('subcontract_factory_profile_id', $sub_factory_id);
                    })
                    ->when($search_query, function ($query) use ($search_query) {
                        $query->where('floor_name', 'LIKE', $search_query . '%');
                    })
                    ->limit(20)
                    ->get()
                    ->map(function ($item, $key) {
                        $factory = $item->factory->factory_name ?? '';
                        $data['id'] = $item->id;
                        $data['text'] = $item->floor_name . '[' . $factory . ']';
                        return $data;
                    });
            }
            return response()->json([
                'data' => $data,
                'error' => null,
                'message' => 'Success',
                'status' => 200,
            ]);
        } catch (Exception $e) {
            return response()->json([
                'data' => [],
                'error' => $e->getMessage(),
                'message' => 'Something went wrong',
                'status' => 500,
            ]);
        }
    }

    public function searchFinishingFloors(Request $request): JsonResponse
    {
        try {
            $data = [];
            $sub_factory_id = $request->sub_factory_id ?? null;
            $search_query = $request->search_query ?? null;
            $factory_id = $request->factory_id ?? null;
            if (!$sub_factory_id) {
                $data = [];
            } else {
                $data = SubcontractFinishingFloor::query()
                    ->withoutGlobalScope('factoryId')
                    ->where('status', 1)
                    ->when($factory_id, function ($query) use ($factory_id) {
                        $query->where('factory_id', $factory_id);
                    })
                    ->when(!$factory_id, function ($query) use ($factory_id) {
                        $query->where('factory_id', factoryId());
                    })
                    ->where('subcontract_factory_profile_id', $sub_factory_id)
                    ->when($search_query, function ($query) use ($search_query) {
                        $query->where('floor_name', 'LIKE', $search_query . '%');
                    })
                    ->limit(20)
                    ->get()
                    ->map(function ($item, $key) {
                        $factory = $item->factory->factory_name ?? '';
                        $data['id'] = $item->id;
                        $data['text'] = $item->floor_name . '[' . $factory . ']';
                        return $data;
                    });
            }
            return response()->json([
                'data' => $data,
                'error' => null,
                'message' => 'Success',
                'status' => 200,
            ]);
        } catch (Exception $e) {
            return response()->json([
                'data' => [],
                'error' => $e->getMessage(),
                'message' => 'Something went wrong',
                'status' => 500,
            ]);
        }
    }

    public function searchFinishingTables(Request $request): JsonResponse
    {
        try {
            $data = [];
            $finishing_floor_id = $request->finishing_floor_id ?? null;
            $sub_factory_id = $request->sub_factory_id ?? null;
            $search_query = $request->search_query ?? null;
            if (!$finishing_floor_id) {
                return response()->json([
                    'data' => $data,
                    'error' => null,
                    'message' => 'Data not found',
                    'status' => 200,
                ]);
            }
            if (!$sub_factory_id) {
                $data = [];
            } else {
                $data = SubcontractFinishingTable::query()
                    ->withoutGlobalScope('factoryId')
                    ->where('status', 1)
                    ->where('subcontract_factory_profile_id', $sub_factory_id)
                    ->when($finishing_floor_id, function ($query) use ($finishing_floor_id) {
                        $query->where('subcontract_finishing_floor_id', $finishing_floor_id);
                    })
                    ->when($search_query, function ($query) use ($search_query) {
                        $query->where('table_name', 'LIKE', $search_query . '%');
                    })
                    ->limit(20)
                    ->get()
                    ->map(function ($item, $key) {
                        $factory = $item->factory->factory_name ?? '';
                        $data['id'] = $item->id;
                        $data['text'] = $item->table_name . '[' . $factory . ']';
                        return $data;
                    });
            }
            return response()->json([
                'data' => $data,
                'error' => null,
                'message' => 'Success',
                'status' => 200,
            ]);
        } catch (Exception $e) {
            return response()->json([
                'data' => [],
                'error' => $e->getMessage(),
                'message' => 'Something went wrong',
                'status' => 500,
            ]);
        }
    }
}
