<?php

namespace SkylarkSoft\GoRMG\Subcontract\Controllers\Reports;

use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Excel;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use PDF;
use SkylarkSoft\GoRMG\Subcontract\Exports\OrderWiseStockReportExport;
use SkylarkSoft\GoRMG\Subcontract\Models\Libraries\SubTextileOperation;
use SkylarkSoft\GoRMG\Subcontract\Models\SubTextileModels\DyeingProcess\SubDyeingBatchDetail;
use SkylarkSoft\GoRMG\Subcontract\Models\SubTextileModels\SubGreyStoreReceiveDetails;
use SkylarkSoft\GoRMG\Subcontract\PackageConst;
use SkylarkSoft\GoRMG\SystemSettings\Models\Buyer;
use SkylarkSoft\GoRMG\SystemSettings\Models\Color;

class OrderWiseStockReportController extends Controller
{
    public function index()
    {
        $suppliers = Buyer::query()->pluck('name', 'id')
            ->prepend('Select', '');

        $operations = SubTextileOperation::query()->pluck('name', 'id')
            ->prepend('Select', 0);

        $colors = Color::query()->pluck('name', 'id')
            ->prepend('Select', 0);

        return view(PackageConst::VIEW_PATH . 'report.orderWiseStockReport.index', [
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

        $receiveDetails = SubGreyStoreReceiveDetails::query()
            ->with([
                'textileOrder',
                'operation',
                'fabricType',
                'color',
                'supplier',
            ])
            ->selectRaw('*, SUM(receive_qty) AS total_receive_qty')
            ->when($order, function (Builder $query) use ($order) {
                return $query->where('sub_textile_order_id', $order);
            })
            ->when($supplier, function (Builder $query) use ($supplier) {
                return $query->where('supplier_id', $supplier);
            })
            ->when($color, function (Builder $query) use ($color) {
                return $query->where('color_id', $color);
            })
            ->when($operation, function (Builder $query) use ($operation) {
                return $query->where('sub_textile_operation_id', $operation);
            })
            ->when($fromDate && $toDate, function (Builder $query) use ($fromDate, $toDate) {
                return $query->whereHas('textileOrder', function (Builder $q) use ($fromDate, $toDate) {
                    return $q->whereBetween('receive_date', [$fromDate, $toDate]);
                });
            })
            ->groupBy([
                'sub_textile_order_detail_id',
                'unit_of_measurement_id',
                'fabric_composition_id',
                'fabric_description',
                'fabric_type_id',
                'dia_type_id',
                'finish_dia',
                'color_id',
                'total_roll',
                'gsm',
            ])
            ->get();

        $orderDetailIds = collect($receiveDetails)->pluck('sub_textile_order_detail_id')->unique();

        $batchDetails = SubDyeingBatchDetail::query()
            ->with([
                'goodsDeliveryDetails.subDyeingGoodsDelivery',
                'subDyeingBatch',
                'color',
            ])
            ->selectRaw('*, SUM(batch_weight) as total_batch_qty')
            ->whereIn('sub_textile_order_detail_id', $orderDetailIds)
            ->groupBy(['sub_textile_order_detail_id', 'sub_dyeing_batch_id'])
            ->get()
            ->map(function ($batchDetail) {
                $deliveryDetails = $batchDetail->goodsDeliveryDetails
                    ->map(function ($deliveryDetail) {
                        return [
                            'delivery_date' => $deliveryDetail->subDyeingGoodsDelivery->delivery_date,
                            'grey_delivery' => $deliveryDetail->grey_weight_fabric,
                            'finish_delivery_qty' => $deliveryDetail->delivery_qty,
                            'rate' => $deliveryDetail->rate,
                            'currency' => $deliveryDetail->subDyeingGoodsDelivery->currency_value,
                            'total_value' => ((float) $deliveryDetail->grey_weight_fabric) * ((float) $deliveryDetail->rate),
                            'shade' => $deliveryDetail->shade,
                            'remarks' => $deliveryDetail->remarks,
                        ];
                    });
                if ($batchDetail->goodsDeliveryDetails->count() <= 0) {
                    $deliveryDetails = [
                        [
                            'delivery_date' => null,
                            'grey_delivery' => 0,
                            'finish_delivery_qty' => 0,
                            'rate' => 0,
                            'currency' => null,
                            'total_value' => 0,
                            'shade' => null,
                            'remarks' => null,
                        ],
                    ];
                }

                return [
                    'sub_textile_operation_id' => $batchDetail->sub_textile_operation_id,
                    'sub_textile_order_id' => $batchDetail->sub_textile_order_id,
                    'sub_grey_store_id' => $batchDetail->sub_grey_store_id,
                    'fabric_composition_id' => $batchDetail->fabric_composition_id,
                    'fabric_type_id' => $batchDetail->fabric_type_id,
                    'ld_no' => $batchDetail->ld_no,
                    'color_type_id' => $batchDetail->color_type_id,
                    'yarn_details' => $batchDetail->yarn_details,
                    'unit_of_measurement_id' => $batchDetail->unit_of_measurement_id,
                    'material_description' => $batchDetail->material_description,
                    'order_id' => $batchDetail->sub_textile_order_id,
                    'sub_textile_order_detail_id' => $batchDetail->sub_textile_order_detail_id,
                    'batch_date' => $batchDetail->subDyeingBatch->batch_date,
                    'batch_no' => $batchDetail->subDyeingBatch->batch_no,
                    'fabric_dia' => $batchDetail->finish_dia,
                    'dia_type' => $batchDetail->dia_type_value['name'],
                    'gsm' => $batchDetail->gsm,
                    'color' => $batchDetail->color->name,
                    'delivery_balance' => $batchDetail->total_batch_qty - collect($deliveryDetails)->sum('grey_delivery'),
                    'delivery_details' => $deliveryDetails,
                    'batch_qty' => $batchDetail->total_batch_qty,
                    'delivery_count' => collect($deliveryDetails)->count(),
                ];
            });

        $reportData = [];
        foreach ($receiveDetails as $receiveDetail) {
            $receiveBatchDetails = $batchDetails
                ->where('sub_textile_operation_id', $receiveDetail->sub_textile_operation_id)
                ->where('sub_textile_order_id', $receiveDetail->sub_textile_order_id)
                ->where('sub_textile_order_detail_id', $receiveDetail->sub_textile_order_detail_id)
                ->where('sub_grey_store_id', $receiveDetail->sub_grey_store_id)
                ->where('fabric_composition_id', $receiveDetail->fabric_composition_id)
                ->where('fabric_type_id', $receiveDetail->fabric_type_id)
                ->where('ld_no', $receiveDetail->ld_no)
                ->where('color_type_id', $receiveDetail->color_type_id)
                ->where('material_description', $receiveDetail->fabric_description)
                ->where('yarn_details', $receiveDetail->yarn_details)
                ->where('unit_of_measurement_id', $receiveDetail->unit_of_measurement_id);

            if ($receiveBatchDetails->count() <= 0) {
                $receiveBatchDetails = [[
                    'material_description' => null,
                    'order_id' => null,
                    'sub_textile_order_detail_id' => null,
                    'batch_date' => null,
                    'batch_no' => null,
                    'fabric_dia' => null,
                    'dia_type' => null,
                    'gsm' => null,
                    'color' => null,
                    'delivery_balance' => 0,
                    'delivery_details' => [
                        [
                            'delivery_date' => null,
                            'grey_delivery' => 0,
                            'finish_delivery_qty' => 0,
                            'rate' => 0,
                            'currency' => null,
                            'total_value' => 0,
                            'shade' => null,
                            'remarks' => null,
                        ],
                    ],
                    'batch_qty' => 0,
                    'delivery_count' => 1,
                ]];
            }

            $reportData[] = [
                'id' => $receiveDetail->id,
                'sub_textile_order_id' => $receiveDetail->sub_textile_order_id,
                'sub_textile_order_detail_id' => $receiveDetail->sub_textile_order_detail_id,
                'date' => $receiveDetail->textileOrder->receive_date,
                'party_name' => $receiveDetail->supplier->name,
                'order_no' => $receiveDetail->textileOrder->order_no,
                'operation' => $receiveDetail->operation->name,
                'fabric_description' => $receiveDetail->fabric_description,
                'fabric_type' => $receiveDetail->fabricType->construction_name,
                'color' => $receiveDetail->color->name,
                'fabric_dia' => $receiveDetail->finish_dia,
                'dia_type' => $receiveDetail->dia_type_value['name'],
                'gsm' => $receiveDetail->gsm,
                'received_qty' => $receiveDetail->total_receive_qty,
                'grey_stock_qty' => $receiveDetail->total_receive_qty - collect($receiveBatchDetails)->sum('batch_qty'),
                'batch_details' => $receiveBatchDetails,
                'total_batch_qty' => collect($receiveBatchDetails)->sum('batch_qty'),
                'batch_count' => collect($receiveBatchDetails)->count(),
                'total_rows' => collect($receiveBatchDetails)->sum('delivery_count'),
            ];
        }
        //return $reportData;
        return view(PackageConst::VIEW_PATH . 'report.orderWiseStockReport.table', [
            'reportData' => collect($reportData)->sortBy('date'),
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

        $receiveDetails = SubGreyStoreReceiveDetails::query()
            ->with([
                'textileOrder',
                'operation',
                'fabricType',
                'color',
                'supplier',
            ])
            ->selectRaw('*, SUM(receive_qty) AS total_receive_qty')
            ->when($order, function (Builder $query) use ($order) {
                return $query->where('sub_textile_order_id', $order);
            })
            ->when($supplier, function (Builder $query) use ($supplier) {
                return $query->where('supplier_id', $supplier);
            })
            ->when($color, function (Builder $query) use ($color) {
                return $query->where('color_id', $color);
            })
            ->when($operation, function (Builder $query) use ($operation) {
                return $query->where('sub_textile_operation_id', $operation);
            })
            ->when($fromDate && $toDate, function (Builder $query) use ($fromDate, $toDate) {
                return $query->whereHas('textileOrder', function (Builder $q) use ($fromDate, $toDate) {
                    return $q->whereBetween('receive_date', [$fromDate, $toDate]);
                });
            })
            ->groupBy([
                'sub_textile_order_detail_id',
                'unit_of_measurement_id',
                'fabric_composition_id',
                'fabric_description',
                'fabric_type_id',
                'dia_type_id',
                'finish_dia',
                'color_id',
                'total_roll',
                'gsm',
            ])
            ->get();

        $orderDetailIds = collect($receiveDetails)->pluck('sub_textile_order_detail_id')->unique();

        $batchDetails = SubDyeingBatchDetail::query()
            ->with([
                'goodsDeliveryDetails.subDyeingGoodsDelivery',
                'subDyeingBatch',
                'color',
            ])
            ->selectRaw('*, SUM(batch_weight) as total_batch_qty')
            ->whereIn('sub_textile_order_detail_id', $orderDetailIds)
            ->groupBy(['sub_textile_order_detail_id', 'sub_dyeing_batch_id'])
            ->get()
            ->map(function ($batchDetail) {
                $deliveryDetails = $batchDetail->goodsDeliveryDetails
                    ->map(function ($deliveryDetail) {
                        return [
                            'delivery_date' => $deliveryDetail->subDyeingGoodsDelivery->delivery_date,
                            'grey_delivery' => $deliveryDetail->grey_weight_fabric,
                            'finish_delivery_qty' => $deliveryDetail->delivery_qty,
                            'rate' => $deliveryDetail->rate,
                            'currency' => $deliveryDetail->subDyeingGoodsDelivery->currency_value,
                            'total_value' => ((float) $deliveryDetail->grey_weight_fabric) * ((float) $deliveryDetail->rate),
                            'shade' => $deliveryDetail->shade,
                            'remarks' => $deliveryDetail->remarks,
                        ];
                    });
                if ($batchDetail->goodsDeliveryDetails->count() <= 0) {
                    $deliveryDetails = [
                        [
                            'delivery_date' => null,
                            'grey_delivery' => 0,
                            'finish_delivery_qty' => 0,
                            'rate' => 0,
                            'currency' => null,
                            'total_value' => 0,
                            'shade' => null,
                            'remarks' => null,
                        ],
                    ];
                }

                return [
                    'sub_textile_operation_id' => $batchDetail->sub_textile_operation_id,
                    'sub_textile_order_id' => $batchDetail->sub_textile_order_id,
                    'sub_grey_store_id' => $batchDetail->sub_grey_store_id,
                    'fabric_composition_id' => $batchDetail->fabric_composition_id,
                    'fabric_type_id' => $batchDetail->fabric_type_id,
                    'ld_no' => $batchDetail->ld_no,
                    'color_type_id' => $batchDetail->color_type_id,
                    'yarn_details' => $batchDetail->yarn_details,
                    'unit_of_measurement_id' => $batchDetail->unit_of_measurement_id,
                    'material_description' => $batchDetail->material_description,
                    'order_id' => $batchDetail->sub_textile_order_id,
                    'sub_textile_order_detail_id' => $batchDetail->sub_textile_order_detail_id,
                    'batch_date' => $batchDetail->subDyeingBatch->batch_date,
                    'batch_no' => $batchDetail->subDyeingBatch->batch_no,
                    'fabric_dia' => $batchDetail->finish_dia,
                    'dia_type' => $batchDetail->dia_type_value['name'],
                    'gsm' => $batchDetail->gsm,
                    'color' => $batchDetail->color->name,
                    'delivery_balance' => $batchDetail->total_batch_qty - collect($deliveryDetails)->sum('grey_delivery'),
                    'delivery_details' => $deliveryDetails,
                    'batch_qty' => $batchDetail->total_batch_qty,
                    'delivery_count' => collect($deliveryDetails)->count(),
                ];
            });

        $reportData = [];
        foreach ($receiveDetails as $receiveDetail) {
            $receiveBatchDetails = $batchDetails
                ->where('sub_textile_operation_id', $receiveDetail->sub_textile_operation_id)
                ->where('sub_textile_order_id', $receiveDetail->sub_textile_order_id)
                ->where('sub_textile_order_detail_id', $receiveDetail->sub_textile_order_detail_id)
                ->where('sub_grey_store_id', $receiveDetail->sub_grey_store_id)
                ->where('fabric_composition_id', $receiveDetail->fabric_composition_id)
                ->where('fabric_type_id', $receiveDetail->fabric_type_id)
                ->where('ld_no', $receiveDetail->ld_no)
                ->where('color_type_id', $receiveDetail->color_type_id)
                ->where('material_description', $receiveDetail->fabric_description)
                ->where('yarn_details', $receiveDetail->yarn_details)
                ->where('unit_of_measurement_id', $receiveDetail->unit_of_measurement_id);

            if ($receiveBatchDetails->count() <= 0) {
                $receiveBatchDetails = [[
                    'material_description' => null,
                    'order_id' => null,
                    'sub_textile_order_detail_id' => null,
                    'batch_date' => null,
                    'batch_no' => null,
                    'fabric_dia' => null,
                    'dia_type' => null,
                    'gsm' => null,
                    'color' => null,
                    'delivery_balance' => 0,
                    'delivery_details' => [
                        [
                            'delivery_date' => null,
                            'grey_delivery' => 0,
                            'finish_delivery_qty' => 0,
                            'rate' => 0,
                            'currency' => null,
                            'total_value' => 0,
                            'shade' => null,
                            'remarks' => null,
                        ],
                    ],
                    'batch_qty' => 0,
                    'delivery_count' => 1,
                ]];
            }

            $reportData[] = [
                'id' => $receiveDetail->id,
                'sub_textile_order_id' => $receiveDetail->sub_textile_order_id,
                'sub_textile_order_detail_id' => $receiveDetail->sub_textile_order_detail_id,
                'date' => $receiveDetail->textileOrder->receive_date,
                'party_name' => $receiveDetail->supplier->name,
                'order_no' => $receiveDetail->textileOrder->order_no,
                'operation' => $receiveDetail->operation->name,
                'fabric_description' => $receiveDetail->fabric_description,
                'fabric_type' => $receiveDetail->fabricType->construction_name,
                'color' => $receiveDetail->color->name,
                'fabric_dia' => $receiveDetail->finish_dia,
                'dia_type' => $receiveDetail->dia_type_value['name'],
                'gsm' => $receiveDetail->gsm,
                'received_qty' => $receiveDetail->total_receive_qty,
                'grey_stock_qty' => $receiveDetail->total_receive_qty - collect($receiveBatchDetails)->sum('batch_qty'),
                'batch_details' => $receiveBatchDetails,
                'total_batch_qty' => collect($receiveBatchDetails)->sum('batch_qty'),
                'batch_count' => collect($receiveBatchDetails)->count(),
                'total_rows' => collect($receiveBatchDetails)->sum('delivery_count'),
            ];
        }
        $pdf = PDF::setOption('enable-local-file-access', true)
            ->loadView(PackageConst::VIEW_PATH . 'report.orderWiseStockReport.pdf', [
                'reportData' => collect($reportData)->sortBy('date'),
            ])->setPaper('a4')->setOrientation('landscape')->setOptions([
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

        $receiveDetails = SubGreyStoreReceiveDetails::query()
            ->with([
                'textileOrder',
                'operation',
                'fabricType',
                'color',
                'supplier',
            ])
            ->selectRaw('*, SUM(receive_qty) AS total_receive_qty')
            ->when($order, function (Builder $query) use ($order) {
                return $query->where('sub_textile_order_id', $order);
            })
            ->when($supplier, function (Builder $query) use ($supplier) {
                return $query->where('supplier_id', $supplier);
            })
            ->when($color, function (Builder $query) use ($color) {
                return $query->where('color_id', $color);
            })
            ->when($operation, function (Builder $query) use ($operation) {
                return $query->where('sub_textile_operation_id', $operation);
            })
            ->when($fromDate && $toDate, function (Builder $query) use ($fromDate, $toDate) {
                return $query->whereHas('textileOrder', function (Builder $q) use ($fromDate, $toDate) {
                    return $q->whereBetween('receive_date', [$fromDate, $toDate]);
                });
            })
            ->groupBy([
                'sub_textile_order_detail_id',
                'unit_of_measurement_id',
                'fabric_composition_id',
                'fabric_description',
                'fabric_type_id',
                'dia_type_id',
                'finish_dia',
                'color_id',
                'total_roll',
                'gsm',
            ])
            ->get();

        $orderDetailIds = collect($receiveDetails)->pluck('sub_textile_order_detail_id')->unique();

        $batchDetails = SubDyeingBatchDetail::query()
            ->with([
                'goodsDeliveryDetails.subDyeingGoodsDelivery',
                'subDyeingBatch',
                'color',
            ])
            ->selectRaw('*, SUM(batch_weight) as total_batch_qty')
            ->whereIn('sub_textile_order_detail_id', $orderDetailIds)
            ->groupBy(['sub_textile_order_detail_id', 'sub_dyeing_batch_id'])
            ->get()
            ->map(function ($batchDetail) {
                $deliveryDetails = $batchDetail->goodsDeliveryDetails
                    ->map(function ($deliveryDetail) {
                        return [
                            'delivery_date' => $deliveryDetail->subDyeingGoodsDelivery->delivery_date,
                            'grey_delivery' => $deliveryDetail->grey_weight_fabric,
                            'finish_delivery_qty' => $deliveryDetail->delivery_qty,
                            'rate' => $deliveryDetail->rate,
                            'currency' => $deliveryDetail->subDyeingGoodsDelivery->currency_value,
                            'total_value' => ((float) $deliveryDetail->grey_weight_fabric) * ((float) $deliveryDetail->rate),
                            'shade' => $deliveryDetail->shade,
                            'remarks' => $deliveryDetail->remarks,
                        ];
                    });
                if ($batchDetail->goodsDeliveryDetails->count() <= 0) {
                    $deliveryDetails = [
                        [
                            'delivery_date' => null,
                            'grey_delivery' => 0,
                            'finish_delivery_qty' => 0,
                            'rate' => 0,
                            'currency' => null,
                            'total_value' => 0,
                            'shade' => null,
                            'remarks' => null,
                        ],
                    ];
                }

                return [
                    'sub_textile_operation_id' => $batchDetail->sub_textile_operation_id,
                    'sub_textile_order_id' => $batchDetail->sub_textile_order_id,
                    'sub_grey_store_id' => $batchDetail->sub_grey_store_id,
                    'fabric_composition_id' => $batchDetail->fabric_composition_id,
                    'fabric_type_id' => $batchDetail->fabric_type_id,
                    'ld_no' => $batchDetail->ld_no,
                    'color_type_id' => $batchDetail->color_type_id,
                    'yarn_details' => $batchDetail->yarn_details,
                    'unit_of_measurement_id' => $batchDetail->unit_of_measurement_id,
                    'material_description' => $batchDetail->material_description,
                    'order_id' => $batchDetail->sub_textile_order_id,
                    'sub_textile_order_detail_id' => $batchDetail->sub_textile_order_detail_id,
                    'batch_date' => $batchDetail->subDyeingBatch->batch_date,
                    'batch_no' => $batchDetail->subDyeingBatch->batch_no,
                    'fabric_dia' => $batchDetail->finish_dia,
                    'dia_type' => $batchDetail->dia_type_value['name'],
                    'gsm' => $batchDetail->gsm,
                    'color' => $batchDetail->color->name,
                    'delivery_balance' => $batchDetail->total_batch_qty - collect($deliveryDetails)->sum('grey_delivery'),
                    'delivery_details' => $deliveryDetails,
                    'batch_qty' => $batchDetail->total_batch_qty,
                    'delivery_count' => collect($deliveryDetails)->count(),
                ];
            });

        $reportData = [];
        foreach ($receiveDetails as $receiveDetail) {
            $receiveBatchDetails = $batchDetails
                ->where('sub_textile_operation_id', $receiveDetail->sub_textile_operation_id)
                ->where('sub_textile_order_id', $receiveDetail->sub_textile_order_id)
                ->where('sub_textile_order_detail_id', $receiveDetail->sub_textile_order_detail_id)
                ->where('sub_grey_store_id', $receiveDetail->sub_grey_store_id)
                ->where('fabric_composition_id', $receiveDetail->fabric_composition_id)
                ->where('fabric_type_id', $receiveDetail->fabric_type_id)
                ->where('ld_no', $receiveDetail->ld_no)
                ->where('color_type_id', $receiveDetail->color_type_id)
                ->where('material_description', $receiveDetail->fabric_description)
                ->where('yarn_details', $receiveDetail->yarn_details)
                ->where('unit_of_measurement_id', $receiveDetail->unit_of_measurement_id);

            if ($receiveBatchDetails->count() <= 0) {
                $receiveBatchDetails = [[
                    'material_description' => null,
                    'order_id' => null,
                    'sub_textile_order_detail_id' => null,
                    'batch_date' => null,
                    'batch_no' => null,
                    'fabric_dia' => null,
                    'dia_type' => null,
                    'gsm' => null,
                    'color' => null,
                    'delivery_balance' => 0,
                    'delivery_details' => [
                        [
                            'delivery_date' => null,
                            'grey_delivery' => 0,
                            'finish_delivery_qty' => 0,
                            'rate' => 0,
                            'currency' => null,
                            'total_value' => 0,
                            'shade' => null,
                            'remarks' => null,
                        ],
                    ],
                    'batch_qty' => 0,
                    'delivery_count' => 1,
                ]];
            }

            $reportData[] = [
                'id' => $receiveDetail->id,
                'sub_textile_order_id' => $receiveDetail->sub_textile_order_id,
                'sub_textile_order_detail_id' => $receiveDetail->sub_textile_order_detail_id,
                'date' => $receiveDetail->textileOrder->receive_date,
                'party_name' => $receiveDetail->supplier->name,
                'order_no' => $receiveDetail->textileOrder->order_no,
                'operation' => $receiveDetail->operation->name,
                'fabric_description' => $receiveDetail->fabric_description,
                'fabric_type' => $receiveDetail->fabricType->construction_name,
                'color' => $receiveDetail->color->name,
                'fabric_dia' => $receiveDetail->finish_dia,
                'dia_type' => $receiveDetail->dia_type_value['name'],
                'gsm' => $receiveDetail->gsm,
                'received_qty' => $receiveDetail->total_receive_qty,
                'grey_stock_qty' => $receiveDetail->total_receive_qty - collect($receiveBatchDetails)->sum('batch_qty'),
                'batch_details' => $receiveBatchDetails,
                'total_batch_qty' => collect($receiveBatchDetails)->sum('batch_qty'),
                'batch_count' => collect($receiveBatchDetails)->count(),
                'total_rows' => collect($receiveBatchDetails)->sum('delivery_count'),
            ];
        }

        return Excel::download(new OrderWiseStockReportExport(collect($reportData)->sortBy('date'), ), 'challan_wise_receive_report.xlsx');
    }
}
