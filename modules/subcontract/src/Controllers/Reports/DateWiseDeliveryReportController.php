<?php

namespace SkylarkSoft\GoRMG\Subcontract\Controllers\Reports;

use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Excel;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use PDF;
use SkylarkSoft\GoRMG\Subcontract\Exports\DateWiseDeliveryReportExport;
use SkylarkSoft\GoRMG\Subcontract\Models\SubTextileModels\SubDyeingGoodsDeliveryDetail;
use SkylarkSoft\GoRMG\Subcontract\PackageConst;
use SkylarkSoft\GoRMG\SystemSettings\Models\Buyer;
use SkylarkSoft\GoRMG\SystemSettings\Models\Color;

class DateWiseDeliveryReportController extends Controller
{
    public function index()
    {
        $suppliers = Buyer::query()->pluck('name', 'id')
            ->prepend('Select', '');

        $colors = Color::query()->pluck('name', 'id')
            ->prepend('Select', 0);

        return view(PackageConst::VIEW_PATH . 'report.date-wise-delivery-report.index', [
            'suppliers' => $suppliers,
            'colors' => $colors,
        ]);
    }

    public function getReport(Request $request)
    {
        $supplier_id = $request->get('supplier_id');
        $order_id = $request->get('order_id');
        $color_id = $request->get('color_id');
        $batch_id = $request->get('batch_id');
        $form_date = $request->get('form_date') ?
            Carbon::make($request->get('form_date'))->format('Y-m-d')
            : null;
        $to_date = $request->get('to_date') ?
            Carbon::make($request->get('to_date'))->format('Y-m-d')
            : null;
//        dd($form_date,$to_date);

        $dyeingGoodsDelivery = SubDyeingGoodsDeliveryDetail::query()
            ->with([
                'subDyeingGoodsDelivery.supplier',
                'color',
            ])
            ->when($supplier_id, function (Builder $builder) use ($supplier_id) {
                $builder->whereHas('subDyeingGoodsDelivery', function ($query) use ($supplier_id) {
                    $query->where('supplier_id', $supplier_id);
                });
            })
            ->when($order_id, function (Builder $builder) use ($order_id) {
                $builder->where('order_id', $order_id);
            })
            ->when($color_id, function (Builder $builder) use ($color_id) {
                $builder->where('color_id', $color_id);
            })
            ->when($batch_id, function (Builder $builder) use ($batch_id) {
                $builder->where('batch_id', $batch_id);
            })
            ->when($form_date && $to_date, function (Builder $builder) use ($form_date, $to_date) {
                $builder->whereHas('subDyeingGoodsDelivery', function (Builder $query) use ($form_date, $to_date) {
                    $query->whereBetween('delivery_date', [$form_date, $to_date]);
                });
            })
            ->get()
            ->map(function ($goodsDelivery) {
//                dump($goodsDelivery->grey_weight_fabric);
                $greyWeight = $goodsDelivery->grey_weight_fabric - $goodsDelivery->delivery_qty;
                $processLoss = $goodsDelivery->grey_weight_fabric ? $greyWeight / $goodsDelivery->grey_weight_fabric : 0;
                $processLoss = $processLoss * 100;
                $rate = $goodsDelivery->grey_weight_fabric ? $goodsDelivery->total_value / $goodsDelivery->grey_weight_fabric : 0;

                return [
                    'date' => $goodsDelivery->subDyeingGoodsDelivery->delivery_date,
                    'party_name' => $goodsDelivery->subDyeingGoodsDelivery->supplier->name,
                    'delivery_uid' => $goodsDelivery->subDyeingGoodsDelivery->goods_delivery_uid,
                    'entry_basis' => $goodsDelivery->subDyeingGoodsDelivery->entry_basis_value,
                    'batch_no' => $goodsDelivery->batch_no,
                    'order_no' => $goodsDelivery->order_no,
                    'color' => $goodsDelivery->color->name,
                    'roll_qty' => $goodsDelivery->total_roll,
                    'grey_delivery' => $goodsDelivery->grey_weight_fabric,
                    'finish_delivery' => $goodsDelivery->delivery_qty,
                    'total_value' => $goodsDelivery->total_value,
                    'process_loss' => $processLoss,
                    'rate' => $rate,
                    'remarks' => $goodsDelivery->remarks,
                ];
            });
//        dd($dyeingGoodsDelivery);
        return view(PackageConst::VIEW_PATH . 'report.date-wise-delivery-report.table', [
            'dyeingGoodsDelivery' => $dyeingGoodsDelivery,
        ]);
    }

    public function pdf(Request $request)
    {
        $supplier_id = $request->get('supplier_id');
        $order_id = $request->get('order_id');
        $color_id = $request->get('color_id');
        $batch_id = $request->get('batch_id');
        $form_date = $request->get('form_date') ?
            Carbon::make($request->get('form_date'))->format('Y-m-d')
            : null;
        $to_date = $request->get('to_date') ?
            Carbon::make($request->get('to_date'))->format('Y-m-d')
            : null;


        $dyeingGoodsDelivery = SubDyeingGoodsDeliveryDetail::query()
            ->with([
                'subDyeingGoodsDelivery.supplier',
                'color',
            ])
            ->when($supplier_id, function (Builder $builder) use ($supplier_id) {
                $builder->whereHas('subDyeingGoodsDelivery', function ($query) use ($supplier_id) {
                    $query->where('supplier_id', $supplier_id);
                });
            })
            ->when($order_id, function (Builder $builder) use ($order_id) {
                $builder->where('order_id', $order_id);
            })
            ->when($color_id, function (Builder $builder) use ($color_id) {
                $builder->where('color_id', $color_id);
            })
            ->when($batch_id, function (Builder $builder) use ($batch_id) {
                $builder->where('batch_id', $batch_id);
            })
            ->when($form_date && $to_date, function (Builder $builder) use ($form_date, $to_date) {
                $builder->whereHas('subDyeingGoodsDelivery', function (Builder $query) use ($form_date, $to_date) {
                    $query->whereBetween('delivery_date', [$form_date, $to_date]);
                });
            })
            ->get()
            ->map(function ($goodsDelivery) {
//                dump($goodsDelivery->grey_weight_fabric);
                $greyWeight = $goodsDelivery->grey_weight_fabric - $goodsDelivery->delivery_qty;
                $processLoss = $goodsDelivery->grey_weight_fabric ? $greyWeight / $goodsDelivery->grey_weight_fabric : 0;
                $processLoss = $processLoss * 100;
                $rate = $goodsDelivery->grey_weight_fabric ? $goodsDelivery->total_value / $goodsDelivery->grey_weight_fabric : 0;

                return [
                    'date' => $goodsDelivery->subDyeingGoodsDelivery->delivery_date,
                    'party_name' => $goodsDelivery->subDyeingGoodsDelivery->supplier->name,
                    'delivery_uid' => $goodsDelivery->subDyeingGoodsDelivery->goods_delivery_uid,
                    'entry_basis' => $goodsDelivery->subDyeingGoodsDelivery->entry_basis_value,
                    'batch_no' => $goodsDelivery->batch_no,
                    'order_no' => $goodsDelivery->order_no,
                    'color' => $goodsDelivery->color->name,
                    'roll_qty' => $goodsDelivery->total_roll,
                    'grey_delivery' => $goodsDelivery->grey_weight_fabric,
                    'finish_delivery' => $goodsDelivery->delivery_qty,
                    'total_value' => $goodsDelivery->total_value,
                    'process_loss' => $processLoss,
                    'rate' => $rate,
                    'remarks' => $goodsDelivery->remarks,
                ];
            });

        $pdf = PDF::setOption('enable-local-file-access', true)
            ->loadView(PackageConst::VIEW_PATH . 'report.date-wise-delivery-report.pdf', [
                'dyeingGoodsDelivery' => $dyeingGoodsDelivery,
            ])->setPaper('a4')->setOptions([
                'header-html' => view('skeleton::pdf.header'),
                'footer-html' => view('skeleton::pdf.footer'),
            ]);

        return $pdf->stream('date_wise_delivery_report.pdf');
    }

    public function excel(Request $request)
    {
        $supplier_id = $request->get('supplier_id');
        $order_id = $request->get('order_id');
        $color_id = $request->get('color_id');
        $batch_id = $request->get('batch_id');
        $form_date = $request->get('form_date') ?
            Carbon::make($request->get('form_date'))->format('Y-m-d')
            : null;
        $to_date = $request->get('to_date') ?
            Carbon::make($request->get('to_date'))->format('Y-m-d')
            : null;


        $dyeingGoodsDelivery = SubDyeingGoodsDeliveryDetail::query()
            ->with([
                'subDyeingGoodsDelivery.supplier',
                'color',
            ])
            ->when($supplier_id, function (Builder $builder) use ($supplier_id) {
                $builder->whereHas('subDyeingGoodsDelivery', function ($query) use ($supplier_id) {
                    $query->where('supplier_id', $supplier_id);
                });
            })
            ->when($order_id, function (Builder $builder) use ($order_id) {
                $builder->where('order_id', $order_id);
            })
            ->when($color_id, function (Builder $builder) use ($color_id) {
                $builder->where('color_id', $color_id);
            })
            ->when($batch_id, function (Builder $builder) use ($batch_id) {
                $builder->where('batch_id', $batch_id);
            })
            ->when($form_date && $to_date, function (Builder $builder) use ($form_date, $to_date) {
                $builder->whereHas('subDyeingGoodsDelivery', function (Builder $query) use ($form_date, $to_date) {
                    $query->whereBetween('delivery_date', [$form_date, $to_date]);
                });
            })
            ->get()
            ->map(function ($goodsDelivery) {
//                dump($goodsDelivery->grey_weight_fabric);
                $greyWeight = $goodsDelivery->grey_weight_fabric - $goodsDelivery->delivery_qty;
                $processLoss = $goodsDelivery->grey_weight_fabric ? $greyWeight / $goodsDelivery->grey_weight_fabric : 0;
                $processLoss = $processLoss * 100;
                $rate = $goodsDelivery->grey_weight_fabric ? $goodsDelivery->total_value / $goodsDelivery->grey_weight_fabric : 0;

                return [
                    'date' => $goodsDelivery->subDyeingGoodsDelivery->delivery_date,
                    'party_name' => $goodsDelivery->subDyeingGoodsDelivery->supplier->name,
                    'delivery_uid' => $goodsDelivery->subDyeingGoodsDelivery->goods_delivery_uid,
                    'entry_basis' => $goodsDelivery->subDyeingGoodsDelivery->entry_basis_value,
                    'batch_no' => $goodsDelivery->batch_no,
                    'order_no' => $goodsDelivery->order_no,
                    'color' => $goodsDelivery->color->name,
                    'roll_qty' => $goodsDelivery->total_roll,
                    'grey_delivery' => $goodsDelivery->grey_weight_fabric,
                    'finish_delivery' => $goodsDelivery->delivery_qty,
                    'total_value' => $goodsDelivery->total_value,
                    'process_loss' => $processLoss,
                    'rate' => $rate,
                    'remarks' => $goodsDelivery->remarks,
                ];
            });

        return Excel::download(new DateWiseDeliveryReportExport($dyeingGoodsDelivery), 'date_wise_delivery_report.xlsx');
    }
}
