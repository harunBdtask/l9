<?php

namespace SkylarkSoft\GoRMG\WarehouseManagement\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Session;
use SkylarkSoft\GoRMG\WarehouseManagement\Exports\WarehouseBuyerStyleColorWiseReportExport;
use SkylarkSoft\GoRMG\WarehouseManagement\Exports\WarehouseBuyerStyleWiseReportExport;
use SkylarkSoft\GoRMG\WarehouseManagement\Exports\WarehouseDailyInReportExport;
use SkylarkSoft\GoRMG\WarehouseManagement\Exports\WarehouseDailyOutReportExport;
use SkylarkSoft\GoRMG\WarehouseManagement\Exports\WarehouseFloorWiseStatusReportExport;
use SkylarkSoft\GoRMG\WarehouseManagement\Models\DateWiseWarehouseInOutReport;
use SkylarkSoft\GoRMG\WarehouseManagement\Models\RackCartonPosition;
use SkylarkSoft\GoRMG\WarehouseManagement\Models\WarehouseCarton;
use SkylarkSoft\GoRMG\WarehouseManagement\Models\WarehouseFloor;

class WarehouseReportController extends Controller
{
    public function dailyInReport(Request $request)
    {
        $from_date = $request->from_date ?? date('Y-m-d');
        $to_date = $request->to_date ?? date('Y-m-d');

        $reports = $this->getDailyInReportData($from_date, $to_date);

        return view('warehouse-management::reports.daily_in_report', [
            'reports' => $reports,
            'from_date' => $from_date,
            'to_date' => $to_date,
        ]);
    }

    private function getDailyInReportData($from_date, $to_date)
    {
        return DateWiseWarehouseInOutReport::where('production_date', '>=', $from_date)
            ->where('production_date', '<=', $to_date)
            ->where('in_garments_qty', '>', 0)
            ->get();
    }

    public function dailyInReportDownload($type, $from_date, $to_date)
    {
        $data['from_date'] = $from_date;
        $data['to_date'] = $to_date;
        $data['reports'] = $this->getDailyInReportData($from_date, $to_date);
        if ($type == 'pdf') {
            $pdf = \PDF::setOption('enable-local-file-access', true)
            ->loadView('warehouse-management::reports.downloads.pdf.daily_in_report_download', $data)
            ->setPaper('a4')->setOptions([
                'header-html' => view('skeleton::pdf.header'),
                'footer-html' => view('skeleton::pdf.footer'),
            ]);

            return $pdf->stream('Daily Warehouse In Report.pdf');
        } else {
            return \Excel::download(new WarehouseDailyInReportExport($data), 'Daily Warehouse In Report.xlsx');
        }
    }

    public function dailyOutReport(Request $request)
    {
        $from_date = $request->from_date ?? date('Y-m-d');
        $to_date = $request->to_date ?? date('Y-m-d');

        $reports = $this->getDailyOutReportData($from_date, $to_date);

        return view('warehouse-management::reports.daily_out_report', [
            'reports' => $reports,
            'from_date' => $from_date,
            'to_date' => $to_date,
        ]);
    }

    private function getDailyOutReportData($from_date, $to_date)
    {
        return DateWiseWarehouseInOutReport::where('production_date', '>=', $from_date)
            ->where('production_date', '<=', $to_date)
            ->where('out_garments_qty', '>', 0)
            ->get();
    }

    public function dailyOutReportDownload($type, $from_date, $to_date)
    {
        $data['from_date'] = $from_date;
        $data['to_date'] = $to_date;
        $data['reports'] = $this->getDailyOutReportData($from_date, $to_date);
        if ($type == 'pdf') {
            $pdf = \PDF::setOption('enable-local-file-access', true)
            ->loadView('warehouse-management::reports.downloads.pdf.daily_out_report_download', $data)
            ->setPaper('a4')->setOptions([
                'header-html' => view('skeleton::pdf.header'),
                'footer-html' => view('skeleton::pdf.footer'),
            ]);

            return $pdf->stream('Daily Warehouse In Report.pdf');
        } else {
            return \Excel::download(new WarehouseDailyOutReportExport($data), 'Daily Warehouse Out Report.xlsx');
        }
    }

    public function floorWiseStatusReport(Request $request)
    {
        $warehouse_floors = WarehouseFloor::orderBy('id', 'desc')->pluck('name', 'id');
        $warehouse_floor_id = null;
        if ($request->isMethod('post')) {
            $request->validate([
                'warehouse_floor_id' => 'required',
            ]);
            $warehouse_floor_id = $request->warehouse_floor_id;
        }

        $reports = $this->getFloorWiseStatusReportData($warehouse_floor_id);

        return view('warehouse-management::reports.floor_wise_status_report', [
            'reports' => $reports,
            'warehouse_floor_id' => $warehouse_floor_id,
            'warehouse_floors' => $warehouse_floors,
        ]);
    }

    private function getFloorWiseStatusReportData($warehouse_floor_id)
    {
        return RackCartonPosition::where('warehouse_floor_id', $warehouse_floor_id)->whereNotNull('warehouse_carton_id')->get();
    }

    public function floorWiseStatusReportDownload($type, $warehouse_floor_id)
    {
        $data['warehouse_floor'] = WarehouseFloor::findOrFail($warehouse_floor_id)->name;
        $data['reports'] = $this->getFloorWiseStatusReportData($warehouse_floor_id);
        if ($type == 'pdf') {
            $pdf = \PDF::setOption('enable-local-file-access', true)
            ->loadView('warehouse-management::reports.downloads.pdf.floor_wise_status_report_download', $data)
            ->setPaper('a4')->setOptions([
                'header-html' => view('skeleton::pdf.header'),
                'footer-html' => view('skeleton::pdf.footer'),
            ]);

            return $pdf->stream('Floor Wise Status Report.pdf');
        } else {
            return \Excel::download(new WarehouseFloorWiseStatusReportExport($data), 'Floor Wise Status Report.xlsx');
        }
    }

    public function buyerStyleWiseStatusReport()
    {
        return view('warehouse-management::reports.buyer_style_wise_report');
    }

    public function getPurchaseOrderWiseWarehouseReport($purchase_order_id)
    {
        $ware_house_report = $this->getPurchaseOrderWiseWarehouseReportData($purchase_order_id);

        $report_view = view('warehouse-management::reports.includes.order_wise_warehouse_report', ['reports' => $ware_house_report])->render();

        return response()->json([
            'html' => $report_view,
        ]);
    }

    public function getPurchaseOrderWiseWarehouseReportData($purchase_order_id)
    {
        return WarehouseCarton::where([
            'purchase_order_id' => $purchase_order_id,
            'rack_allocation_status' => 1,
            'shipment_status' => 0,
        ])->get();
    }

    public function getColorWiseWarehouseReport($purchase_order_id, $color_id)
    {
        $ware_house_report = $this->getColorWiseWarehouseReportData($purchase_order_id, $color_id);

        $report_view = view('warehouse-management::reports.includes.color_wise_warehouse_report', ['reports' => $ware_house_report])->render();

        return response()->json([
            'html' => $report_view,
        ]);
    }

    public function getColorWiseWarehouseReportData($purchase_order_id, $color_id)
    {
        return WarehouseCarton::withoutGlobalScope('factoryId')
            ->with('buyer:id,name', 'order:id,style_name', 'purchaseOrder:id,po_no', 'warehouseFloor:id,name', 'warehouseRack:id,name')
            ->leftJoin('warehouse_carton_details', 'warehouse_carton_details.warehouse_carton_id', 'warehouse_cartons.id')
            ->leftJoin('colors', 'colors.id', 'warehouse_carton_details.color_id')
            ->where([
                'warehouse_cartons.purchase_order_id' => $purchase_order_id,
                'warehouse_cartons.rack_allocation_status' => 1,
                'warehouse_cartons.shipment_status' => 0,
                'warehouse_carton_details.color_id' => $color_id,
            ])
            ->select('warehouse_cartons.id', 'warehouse_cartons.buyer_id', 'warehouse_cartons.order_id', 'warehouse_cartons.purchase_order_id', 'warehouse_cartons.warehouse_floor_id', 'warehouse_cartons.warehouse_rack_id', 'warehouse_carton_details.color_id', 'warehouse_carton_details.quantity', 'colors.name as color_name')
            ->get();
    }

    public function buyerStyleWiseStatusReportDownload($type, $purchase_order_id, $color_id = '')
    {
        if ($color_id) {
            $data['reports'] = $this->getColorWiseWarehouseReportData($purchase_order_id, $color_id);
            if ($type == 'pdf') {
                $pdf = \PDF::setOption('enable-local-file-access', true)
                ->loadView('warehouse-management::reports.downloads.pdf.color_wise_warehouse_report_download', $data)
                ->setPaper('a4')->setOptions([
                    'header-html' => view('skeleton::pdf.header'),
                    'footer-html' => view('skeleton::pdf.footer'),
                ]);

                return $pdf->stream('Buyer-Style-Color Wise Report.pdf');
            } else {
                return \Excel::download(new WarehouseBuyerStyleColorWiseReportExport($data), 'Buyer-Style-Color Wise Report.xlsx');
            }
        } else {
            $data['reports'] = $this->getPurchaseOrderWiseWarehouseReportData($purchase_order_id);
            if ($type == 'pdf') {
                $pdf = \PDF::setOption('enable-local-file-access', true)
                ->loadView('warehouse-management::reports.downloads.pdf.order_wise_warehouse_report_download', $data)
                ->setPaper('a4')->setOptions([
                    'header-html' => view('skeleton::pdf.header'),
                    'footer-html' => view('skeleton::pdf.footer'),
                ]);

                return $pdf->stream('Buyer-Style Wise Report.pdf');
            } else {
                return \Excel::download(new WarehouseBuyerStyleWiseReportExport($data), 'Buyer-Style Wise Report.xlsx');
            }
        }
    }

    public function scanBarcodeCheck(Request $request)
    {
        $report = null;
        $barcode_no = $request->barcode_no ?? null;
        if ($request->isMethod('post')) {
            $request->validate([
                'barcode_no' => 'required',
            ]);
            $barcode_no = $request->barcode_no;
            $reportQuery = WarehouseCarton::with('warehouseCartonDetails')
                ->where('barcode_no', $barcode_no);
            if ($reportQuery->count()) {
                $report = $reportQuery->first();
            } else {
                Session::flash('alert-danger', 'Scan Valid barcode!!');
            }
        } elseif ($barcode_no) {
            $reportQuery = WarehouseCarton::with('warehouseCartonDetails')
                ->where('barcode_no', $barcode_no);
            if ($reportQuery->count()) {
                $report = $reportQuery->first();
            } else {
                Session::flash('alert-danger', 'Scan Valid barcode!!');
            }
        }

        return view('warehouse-management::reports.scan_barcode_check', [
            'report' => $report,
            'barcode_no' => $barcode_no,
        ]);
    }
}
