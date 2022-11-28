<?php

namespace SkylarkSoft\GoRMG\WarehouseManagement\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use SkylarkSoft\GoRMG\WarehouseManagement\Models\DateWiseWarehouseInOutReport;
use SkylarkSoft\GoRMG\WarehouseManagement\Models\RackCartonPosition;
use SkylarkSoft\GoRMG\WarehouseManagement\Models\WarehouseCarton;
use SkylarkSoft\GoRMG\WarehouseManagement\Models\WarehouseFloor;
use Validator;

class WarehouseCartonAllocationController extends Controller
{
    public function index()
    {
        $warehouse_floors = WarehouseFloor::pluck('name', 'id');

        return view('warehouse-management::forms.warehouse_carton_allocation', [
            'warehouse_floors' => $warehouse_floors,
        ]);
    }

    public function getWarehouseRackAllocatedCartons($warehouse_rack_id)
    {
        $rack_carton_positions = RackCartonPosition::where('warehouse_rack_id', $warehouse_rack_id)
            ->whereNotNull('warehouse_carton_id')
            ->get();
        $rack_available_qty = RackCartonPosition::where('warehouse_rack_id', $warehouse_rack_id)
            ->whereNull('warehouse_carton_id')
            ->count();
        $html = view('warehouse-management::pages.rack_carton_positions', [
            'rack_carton_positions' => $rack_carton_positions,
        ])->render();

        return response()->json([
            'html' => $html,
            'rack_available_qty' => $rack_available_qty,
        ]);
    }

    public function storeCartonInRack(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'warehouse_floor_id' => 'required',
            'warehouse_rack_id' => 'required',
            'barcode_no' => 'required',
        ], [
            'warehouse_floor_id.required' => 'This field is required.',
            'warehouse_rack_id.required' => 'This field is required.',
            'barcode_no.required' => 'This field is required.',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'errors' => $validator->errors(),
            ]);
        }

        $warehouse_carton_query = WarehouseCarton::where('barcode_no', $request->barcode_no);
        if ($warehouse_carton_query->count()) {
            $warehouse_carton = $warehouse_carton_query->first();
            if ($warehouse_carton->rack_allocation_status) {
                $html = view('partials.flash_message', [
                    'message_class' => "danger",
                    'message' => "This barcode is already scanned!!",
                ])->render();

                return response()->json([
                    'status' => 'danger',
                    'errors' => null,
                    'message' => $html,
                ]);
            }

            $rack_carton_available_position_query = RackCartonPosition::where('warehouse_rack_id', $request->warehouse_rack_id)
                ->whereNull('warehouse_carton_id')
                ->orderBy('position_no', 'asc');
            if ($rack_carton_available_position_query->count()) {
                $rack_carton_available_position = $rack_carton_available_position_query->first();
                WarehouseCarton::where('barcode_no', $request->barcode_no)->update([
                    'rack_allocation_status' => 1,
                    'warehouse_floor_id' => $request->warehouse_floor_id,
                    'warehouse_rack_id' => $request->warehouse_rack_id,
                ]);
                RackCartonPosition::where('id', $rack_carton_available_position->id)->update([
                    'warehouse_carton_id' => $warehouse_carton->id,
                ]);

                $this->updateDateWiseInOutReport($warehouse_carton);

                $html = view('partials.flash_message', [
                    'message_class' => "success",
                    'message' => "Data updated successfully!!",
                ])->render();

                return response()->json([
                    'status' => 'success',
                    'errors' => null,
                    'message' => $html,
                ]);
            } else {
                $html = view('partials.flash_message', [
                    'message_class' => "danger",
                    'message' => "This rack is full!! Try another rack!!",
                ])->render();

                return response()->json([
                    'status' => 'danger',
                    'errors' => null,
                    'message' => $html,
                ]);
            }
        } else {
            $html = view('partials.flash_message', [
                'message_class' => "danger",
                'message' => "Scan valid barcode!!",
            ])->render();

            return response()->json([
                'status' => 'danger',
                'errors' => null,
                'message' => $html,
            ]);
        }
    }

    private function updateDateWiseInOutReport($warehouse_carton_query)
    {
        // For Carton In
        $date_wise_in_out_report = DateWiseWarehouseInOutReport::where([
            'purchase_order_id' => $warehouse_carton_query->purchase_order_id,
            'production_date' => date('Y-m-d'),
        ])->first();

        if (! $date_wise_in_out_report) {
            $date_wise_in_out_report = new DateWiseWarehouseInOutReport();
            $date_wise_in_out_report->production_date = date('Y-m-d');
            $date_wise_in_out_report->buyer_id = $warehouse_carton_query->buyer_id;
            $date_wise_in_out_report->order_id = $warehouse_carton_query->order_id;
            $date_wise_in_out_report->purchase_order_id = $warehouse_carton_query->purchase_order_id;
        }
        $date_wise_in_out_report->in_garments_qty += $warehouse_carton_query->garments_qty;
        $date_wise_in_out_report->in_carton_qty += 1;
        $date_wise_in_out_report->save();
    }
}
