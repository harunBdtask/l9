<?php

namespace SkylarkSoft\GoRMG\Subcontract\Controllers\Reports;

use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Excel;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use PDF;
use SkylarkSoft\GoRMG\Subcontract\Exports\DyeingLedgerReportExport;
use SkylarkSoft\GoRMG\Subcontract\Models\Libraries\SubTextileOperation;
use SkylarkSoft\GoRMG\Subcontract\Models\SubTextileModels\SubGreyStoreReceiveDetails;
use SkylarkSoft\GoRMG\Subcontract\PackageConst;
use SkylarkSoft\GoRMG\SystemSettings\Models\Buyer;
use SkylarkSoft\GoRMG\SystemSettings\Models\Color;

class DyeingLedgerReportController extends Controller
{
    public function index(Request $request)
    {
        $suppliers = Buyer::query()->pluck('name', 'id')
            ->prepend('Select', '');
        $operations = SubTextileOperation::query()->pluck('name', 'id')
            ->prepend('Select', 0);
        $colors = Color::query()->pluck('name', 'id')
            ->prepend('Select', 0);

        return view(PackageConst::VIEW_PATH . 'report.dyeing-ledger-report.index', [
            'suppliers' => $suppliers,
            'operations' => $operations,
            'colors' => $colors,
        ]);
    }

    public function getReport(Request $request)
    {
        $supplier = $request->get('supplier_id');
        $color = $request->get('color_id');
        $operation = $request->get('operation_id');
        $order = $request->get('order_id');
        $fromDate = $request->get('form_date')
            ? Carbon::make($request->get('form_date'))->format('Y-m-d')
            : null;

        $toDate = $request->get('to_date') ?
            Carbon::make($request->get('to_date'))->format('Y-m-d')
            : null;

        $textileOrders = SubGreyStoreReceiveDetails::query()
            ->with([
                'textileOrder',
                'supplier',
                'operation',
                'fabricType',
                'color',
            ])
            ->select('*', DB::raw('SUM(receive_qty) as total_receive_qty'))
            ->when($supplier, function (Builder $query) use ($supplier) {
                return $query->where('supplier_id', $supplier);
            })
            ->when($color, function (Builder $query) use ($color) {
                return $query->where('color_id', $color);
            })
            ->when($operation, function (Builder $query) use ($operation) {
                return $query->where('sub_textile_operation_id', $operation);
            })
            ->when($order, function (Builder $query) use ($order) {
                return $query->where('sub_textile_order_id', $order);
            })
            ->when($fromDate && $toDate, function (Builder $query) use ($fromDate, $toDate) {
                return $query->whereHas('textileOrder', function (Builder $q) use ($fromDate, $toDate) {
                    return $q->whereBetween('receive_date', [$fromDate, $toDate]);
                });
            })
            ->groupBy('sub_textile_order_id', 'sub_textile_order_detail_id', 'challan_no', 'fabric_description', 'color_id', 'fabric_type_id');

        $subDyeingBatches = $textileOrders->newQuery()
            ->get()->map(function ($detail) {
                return [
                    'id' => $detail->id,
                    'date' => $detail->textileOrder->receive_date,
                    'party_name' => $detail->supplier->name,
                    'challan_no' => $detail->challan_no,
                    'operation' => $detail->operation->name,
                    'order_no' => $detail->textileOrder->order_no,
                    'fabric_description' => $detail->fabric_description,
                    'fabric_type' => $detail->fabricType->construction_name,
                    'color' => $detail->color->name,
                    'fabric_dia' => $detail->finish_dia,
                    'dia_type' => $detail->dia_type_value['name'],
                    'gsm' => $detail->gsm,
                    'total_receive_qty' => $detail->total_receive_qty,
                    'batch_detail_id' => null,
                    'batch_id' => null,
                    'batch_date' => null,
                    'batch_no' => null,
                    'batch_fabric_dia' => null,
                    'batch_qty' => null,
                    'batch_dia_type' => null,
                    'batch_gsm' => null,
                    'batch_color' => null,
                    'grey_stock' => $detail->total_receive_qty,
                ];
            })->toArray();

        return view(PackageConst::VIEW_PATH . 'report.dyeing-ledger-report.table', [
            'reportData' => $subDyeingBatches,
        ]);
    }

    public function pdf(Request $request)
    {
        $supplier = $request->get('supplier_id');
        $color = $request->get('color_id');
        $operation = $request->get('operation_id');
        $order = $request->get('order_id');
        $fromDate = $request->get('form_date')
            ? Carbon::make($request->get('form_date'))->format('Y-m-d')
            : null;

        $toDate = $request->get('to_date') ?
            Carbon::make($request->get('to_date'))->format('Y-m-d')
            : null;

        $textileOrders = SubGreyStoreReceiveDetails::query()
            ->with([
                'textileOrder',
                'supplier',
                'operation',
                'fabricType',
                'color',
            ])
            ->select('*', DB::raw('SUM(receive_qty) as total_receive_qty'))
            ->when($supplier, function (Builder $query) use ($supplier) {
                return $query->where('supplier_id', $supplier);
            })
            ->when($color, function (Builder $query) use ($color) {
                return $query->where('color_id', $color);
            })
            ->when($operation, function (Builder $query) use ($operation) {
                return $query->where('sub_textile_operation_id', $operation);
            })
            ->when($order, function (Builder $query) use ($order) {
                return $query->where('sub_textile_order_id', $order);
            })
            ->when($fromDate && $toDate, function (Builder $query) use ($fromDate, $toDate) {
                return $query->whereHas('textileOrder', function (Builder $q) use ($fromDate, $toDate) {
                    return $q->whereBetween('receive_date', [$fromDate, $toDate]);
                });
            })
            ->groupBy('sub_textile_order_id', 'sub_textile_order_detail_id', 'challan_no', 'fabric_description', 'color_id', 'fabric_type_id');

        $subDyeingBatches = $textileOrders->newQuery()
            ->get()->map(function ($detail) {
                return [
                    'id' => $detail->id,
                    'date' => $detail->textileOrder->receive_date,
                    'party_name' => $detail->supplier->name,
                    'challan_no' => $detail->challan_no,
                    'operation' => $detail->operation->name,
                    'order_no' => $detail->textileOrder->order_no,
                    'fabric_description' => $detail->fabric_description,
                    'fabric_type' => $detail->fabricType->construction_name,
                    'color' => $detail->color->name,
                    'fabric_dia' => $detail->finish_dia,
                    'dia_type' => $detail->dia_type_value['name'],
                    'gsm' => $detail->gsm,
                    'total_receive_qty' => $detail->total_receive_qty,
                    'batch_detail_id' => null,
                    'batch_id' => null,
                    'batch_date' => null,
                    'batch_no' => null,
                    'batch_fabric_dia' => null,
                    'batch_qty' => null,
                    'batch_dia_type' => null,
                    'batch_gsm' => null,
                    'batch_color' => null,
                    'grey_stock' => $detail->total_receive_qty,
                ];
            })->toArray();

        $pdf = PDF::setOption('enable-local-file-access', true)
            ->loadView(PackageConst::VIEW_PATH . 'report.dyeing-ledger-report.pdf', [
                'reportData' => $subDyeingBatches,
            ])->setPaper('a4')->setOptions([
                'header-html' => view('skeleton::pdf.header'),
                'footer-html' => view('skeleton::pdf.footer'),
            ]);

        return $pdf->stream('challan_wise_receive_report.pdf');
    }

    public function excel(Request $request)
    {
        $supplier = $request->get('supplier_id');
        $color = $request->get('color_id');
        $operation = $request->get('operation_id');
        $order = $request->get('order_id');
        $fromDate = $request->get('form_date')
            ? Carbon::make($request->get('form_date'))->format('Y-m-d')
            : null;

        $toDate = $request->get('to_date') ?
            Carbon::make($request->get('to_date'))->format('Y-m-d')
            : null;

        $textileOrders = SubGreyStoreReceiveDetails::query()
            ->with([
                'textileOrder',
                'supplier',
                'operation',
                'fabricType',
                'color',
            ])
            ->select('*', DB::raw('SUM(receive_qty) as total_receive_qty'))
            ->when($supplier, function (Builder $query) use ($supplier) {
                return $query->where('supplier_id', $supplier);
            })
            ->when($color, function (Builder $query) use ($color) {
                return $query->where('color_id', $color);
            })
            ->when($operation, function (Builder $query) use ($operation) {
                return $query->where('sub_textile_operation_id', $operation);
            })
            ->when($order, function (Builder $query) use ($order) {
                return $query->where('sub_textile_order_id', $order);
            })
            ->when($fromDate && $toDate, function (Builder $query) use ($fromDate, $toDate) {
                return $query->whereHas('textileOrder', function (Builder $q) use ($fromDate, $toDate) {
                    return $q->whereBetween('receive_date', [$fromDate, $toDate]);
                });
            })
            ->groupBy('sub_textile_order_id', 'sub_textile_order_detail_id', 'challan_no', 'fabric_description', 'color_id', 'fabric_type_id');

        $subDyeingBatches = $textileOrders->newQuery()
            ->get()->map(function ($detail) {
                return [
                    'id' => $detail->id,
                    'date' => $detail->textileOrder->receive_date,
                    'party_name' => $detail->supplier->name,
                    'challan_no' => $detail->challan_no,
                    'operation' => $detail->operation->name,
                    'order_no' => $detail->textileOrder->order_no,
                    'fabric_description' => $detail->fabric_description,
                    'fabric_type' => $detail->fabricType->construction_name,
                    'color' => $detail->color->name,
                    'fabric_dia' => $detail->finish_dia,
                    'dia_type' => $detail->dia_type_value['name'],
                    'gsm' => $detail->gsm,
                    'total_receive_qty' => $detail->total_receive_qty,
                    'batch_detail_id' => null,
                    'batch_id' => null,
                    'batch_date' => null,
                    'batch_no' => null,
                    'batch_fabric_dia' => null,
                    'batch_qty' => null,
                    'batch_dia_type' => null,
                    'batch_gsm' => null,
                    'batch_color' => null,
                    'grey_stock' => $detail->total_receive_qty,
                ];
            })->toArray();

        return Excel::download(new DyeingLedgerReportExport($subDyeingBatches), 'challan_wise_receive_report.xlsx');
    }
}
