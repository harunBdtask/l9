<?php

namespace SkylarkSoft\GoRMG\WarehouseManagement\Controllers;

use App\Http\Controllers\Controller;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use SkylarkSoft\GoRMG\SystemSettings\Models\Factory;
use SkylarkSoft\GoRMG\WarehouseManagement\Models\DateWiseWarehouseInOutReport;
use SkylarkSoft\GoRMG\WarehouseManagement\Models\RackCartonPosition;
use SkylarkSoft\GoRMG\WarehouseManagement\Models\WarehouseCarton;
use SkylarkSoft\GoRMG\WarehouseManagement\Models\WarehouseShipmentCarton;
use SkylarkSoft\GoRMG\WarehouseManagement\Models\WarehouseShipmentChallan;

class WarehouseShipmentController extends Controller
{
    public function index()
    {
        $challan_no = userId() . time();
        $challan_data = null;
        $warehouse_shipment_cartons = WarehouseShipmentCarton::where([
            'challan_status' => 0,
            'created_by' => userId(),
        ]);
        if ($warehouse_shipment_cartons->count()) {
            $warehouse_shipment_cartons_clone = clone $warehouse_shipment_cartons;
            $challan_no = $warehouse_shipment_cartons->first()->challan_no;
            $challan_data = $warehouse_shipment_cartons_clone->get();
        }

        return view('warehouse-management::forms.warehouse_shipment_scan', [
            'challan_no' => $challan_no,
            'challan_data' => $challan_data,
        ]);
    }

    public function shipmentScanPost(Request $request)
    {
        $request->validate([
            'barcode_no' => 'required',
        ]);

        try {
            DB::beginTransaction();
            $barcode_no = $request->barcode_no;
            $challan_no = $request->challan_no;
            $warehouse_carton_query = WarehouseCarton::where('barcode_no', $barcode_no);
            if (! $warehouse_carton_query->count()) {
                Session::flash('alert-danger', 'Scan valid barcode!!');

                return redirect()->back();
            }
            $warehouse_carton = $warehouse_carton_query->first();
            if (! $warehouse_carton->rack_allocation_status) {
                Session::flash('alert-danger', 'This carton is not allocated in any rack!!');

                return redirect()->back();
            }

            if ($warehouse_carton->shipment_status) {
                Session::flash('alert-danger', 'This carton is already scanned for shipment!!');

                return redirect()->back();
            }

            $warehouse_shipment_carton = new WarehouseShipmentCarton();
            $warehouse_shipment_carton->challan_no = $challan_no;
            $warehouse_shipment_carton->warehouse_carton_id = $warehouse_carton->id;
            $warehouse_shipment_carton->save();

            RackCartonPosition::where('warehouse_carton_id', $warehouse_carton->id)->update(['warehouse_carton_id' => null]);
            WarehouseCarton::where('barcode_no', $barcode_no)->update([
                'shipment_status' => 1,
            ]);
            DB::commit();
            Session::flash('alert-success', 'Data updated successfully!!');

            return redirect('/warehouse-shipment-scan');
        } catch (Exception $e) {
            DB::rollBack();
            Session::flash('alert-danger', 'Something went wrong!!');

            return redirect()->back();
        }
    }

    public function shipmentChallanCreate(Request $request)
    {
        try {
            DB::beginTransaction();
            $warehouse_shipment_challan = new WarehouseShipmentChallan();
            $warehouse_shipment_challan->challan_no = $request->challan_no;
            $warehouse_shipment_challan->save();

            WarehouseShipmentCarton::where('challan_no', $request->challan_no)->update(['challan_status' => 1]);
            $warehouse_shipment_cartons = WarehouseShipmentCarton::where('challan_no', $request->challan_no)->get();

            foreach ($warehouse_shipment_cartons as $warehouse_shipment_carton) {
                $this->updateDateWiseInOutReport($warehouse_shipment_carton->warehouseCarton);
            }

            DB::commit();
            $html = view('partials.flash_message', [
                'message_class' => "success",
                'message' => "Challan Created successfully!!",
            ])->render();

            return response()->json([
                'status' => 'success',
                'errors' => null,
                'message' => $html,
            ]);
        } catch (Exception $e) {
            DB::rollBack();

            $html = view('partials.flash_message', [
                'message_class' => "danger",
                'message' => $e->getMessage(),
            ])->render();

            return response()->json([
                'status' => 'danger',
                'errors' => null,
                'message' => $html,
            ]);
        }
    }

    private function updateDateWiseInOutReport($warehouse_carton)
    {
        // For Carton Out
        $date_wise_in_out_report = DateWiseWarehouseInOutReport::where([
            'purchase_order_id' => $warehouse_carton->purchase_order_id,
            'production_date' => date('Y-m-d'),
        ])->first();

        if (! $date_wise_in_out_report) {
            $date_wise_in_out_report = new DateWiseWarehouseInOutReport();
            $date_wise_in_out_report->production_date = date('Y-m-d');
            $date_wise_in_out_report->buyer_id = $warehouse_carton->buyer_id;
            $date_wise_in_out_report->order_id = $warehouse_carton->order_id;
            $date_wise_in_out_report->purchase_order_id = $warehouse_carton->purchase_order_id;
        }
        $date_wise_in_out_report->out_garments_qty += $warehouse_carton->garments_qty;
        $date_wise_in_out_report->out_carton_qty += 1;
        $date_wise_in_out_report->save();
    }

    public function shipmentChallanList()
    {
        $warehouse_shipment_challans = WarehouseShipmentChallan::with('warehouseShipmentCartons')->orderBy('id', 'desc')->paginate();

        return view('warehouse-management::pages.warehouse_shipment_challan_list', ['warehouse_shipment_challans' => $warehouse_shipment_challans]);
    }

    public function shipmentChallanView($challan_no)
    {
        $warehouse_shipment_challan = WarehouseShipmentChallan::with('warehouseShipmentCartons')->where('challan_no', $challan_no)->first();
        $userFactoryInfo = Factory::findOrFail(factoryId());
        $warehouse_carton_ids = [];
        $reportData = null;
        $i = 0;
        if ($warehouse_shipment_challan->warehouseShipmentCartons) {
            foreach ($warehouse_shipment_challan->warehouseShipmentCartons as $key => $warehouse_shipment_carton) {
                $warehouse_carton_ids[$i] = $warehouse_shipment_carton->warehouse_carton_id;
                $i++;
            }
            if (is_array($warehouse_carton_ids) && count($warehouse_carton_ids)) {
                $reportData = WarehouseCarton::whereIn('id', $warehouse_carton_ids)->get();
            }
        }

        return view('warehouse-management::pages.warehouse_shipment_challan_view', [
            'warehouse_shipment_challan' => $warehouse_shipment_challan,
            'userFactoryInfo' => $userFactoryInfo,
            'reportData' => $reportData,
        ]);
    }

    public function shipmentChallanListSearch(Request $request)
    {
        if ($request->q == '') {
            return redirect('/warehouse-shipment-challans');
        }

        $warehouse_shipment_challans = WarehouseShipmentChallan::with('warehouseShipmentCartons')
            ->where('challan_no', 'like', '%' . $request->q . '%')
            ->orderBy('id', 'desc')->paginate();

        return view('warehouse-management::pages.warehouse_shipment_challan_list', [
            'warehouse_shipment_challans' => $warehouse_shipment_challans,
            'q' => $request->q,
        ]);
    }
}
