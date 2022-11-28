<?php

namespace SkylarkSoft\GoRMG\Subcontract\Controllers\Reports;

use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Excel;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use PDF;
use SkylarkSoft\GoRMG\Subcontract\Exports\DyeingProductionDailyReportExport;
use SkylarkSoft\GoRMG\Subcontract\Models\SubTextileModels\DyeingProcess\SubDyeingBatch;
use SkylarkSoft\GoRMG\Subcontract\Models\SubTextileModels\DyeingProcess\SubDyeingProduction\SubDyeingProduction;
use SkylarkSoft\GoRMG\Subcontract\Models\SubTextileModels\SubTextileOrder;
use SkylarkSoft\GoRMG\Subcontract\PackageConst;
use SkylarkSoft\GoRMG\SystemSettings\Models\Buyer;
use SkylarkSoft\GoRMG\SystemSettings\Models\Factory;

class DyeingProductionDailyReportController extends Controller
{
    public function view()
    {
        $todayDate = Carbon::now()->format('Y-m-d');
        $dyeingProduction = SubDyeingProduction::query()
                            ->with([
                                'subDyeingProductionDetails.color',
                                'subDyeingBatch',
                                'supplier',
                            ])
                            ->where('production_date', $todayDate)
                            ->get();
        $factories = Factory::query()->pluck('factory_name', 'id');
        $batches = SubDyeingBatch::query()->pluck('batch_no', 'id')
                                  ->prepend('Select', '');
        $orders = SubTextileOrder::query()->pluck('order_no', 'id')
                                  ->prepend('Select', '');
        $suppliers = Buyer::query()->pluck('name', 'id')
                                  ->prepend('Select', '');
        //dd($dyeingProduction);
        return view(PackageConst::VIEW_PATH.'report.dyeing-production-daily-report.dyeing_production_daily_report', [
            'dyeingProduction' => $dyeingProduction,
            'factories' => $factories,
            'batches' => $batches,
            'orders' => $orders,
            'suppliers' => $suppliers,
        ]);
    }

    public function getReport(Request $request)
    {
        $factory_id = $request->factory_id;
        $formDate = $request->form_date ? Carbon::make($request->form_date)->format('Y-m-d') : date('Y-m-d');
        $toDate = $request->to_date ? Carbon::make($request->to_date)->format('Y-m-d') : date('Y-m-d');
        $order_id = $request->order_id;
        $batch_id = $request->batch_id;
        $supplier_id = $request->supplier_id;
        //dd($toDate);
        $dyeingProduction = SubDyeingProduction::query()
        ->with([
            'subDyeingProductionDetails.subDyeingProduction',
            'subDyeingBatch',
        ])
        ->when($factory_id, function (Builder $query) use ($factory_id) {
            $query->where('factory_id', $factory_id);
        })
        ->when($order_id, function (Builder $query) use ($order_id) {
            $query->where('order_id', $order_id);
        })
        ->when($batch_id, function (Builder $query) use ($batch_id) {
            $query->where('batch_id', $batch_id);
        })
        ->when($supplier_id, function (Builder $query) use ($supplier_id) {
            $query->where('supplier_id', $supplier_id);
        })
        ->whereBetween('production_date', [$formDate, $toDate])
        ->get();

        return view(PackageConst::VIEW_PATH.'report.dyeing-production-daily-report.dyeing_production_daily_report_table', [
            'dyeingProduction' => $dyeingProduction,
        ]);
    }

    public function pdf(Request $request)
    {
        $todayDate = Carbon::now()->format('Y-m-d');
        $factory_id = $request->factory_id;
        $formDate = $request->form_date ? Carbon::make($request->form_date)->format('Y-m-d') : date('Y-m-d');
        $toDate = $request->to_date ? Carbon::make($request->to_date)->format('Y-m-d') : date('Y-m-d');
        $order_id = $request->order_id;
        $batch_id = $request->batch_id;
        $supplier_id = $request->supplier_id;



        $dyeingProduction = SubDyeingProduction::query()
        ->with([
            'subDyeingProductionDetails.color',
            'subDyeingBatch',
            'supplier',
        ]);
        if ($formDate && $toDate) {
            $dyeingProduction = $dyeingProduction
            ->when($factory_id, function (Builder $query) use ($factory_id) {
                $query->where('factory_id', $factory_id);
            })
            ->when($order_id, function (Builder $query) use ($order_id) {
                $query->where('order_id', $order_id);
            })
            ->when($batch_id, function (Builder $query) use ($batch_id) {
                $query->where('batch_id', $batch_id);
            })
            ->when($supplier_id, function (Builder $query) use ($supplier_id) {
                $query->where('supplier_id', $supplier_id);
            })
            ->whereBetween('production_date', [$formDate, $toDate])
            ->get();
        } else {
            $dyeingProduction = $dyeingProduction->where('production_date', $todayDate)
            ->get();
        }

        $pdf = PDF::setOption('enable-local-file-access', true)
        ->loadView('subcontract::report.pdf.dyeing_production_daily_report_pdf', [
            'dyeingProduction' => $dyeingProduction,
        ])->setPaper('a4')->setOptions([
            'header-html' => view('skeleton::pdf.header'),
            'footer-html' => view('skeleton::pdf.footer'),
        ]);

        return $pdf->stream('dyeing_production_daily_report.pdf');
    }

    public function excel(Request $request)
    {
        $todayDate = Carbon::now()->format('Y-m-d');
        $factory_id = $request->factory_id;
        $formDate = $request->form_date ? Carbon::make($request->form_date)->format('Y-m-d') : date('Y-m-d');
        $toDate = $request->to_date ? Carbon::make($request->to_date)->format('Y-m-d') : date('Y-m-d');
        $order_id = $request->order_id;
        $batch_id = $request->batch_id;
        $supplier_id = $request->supplier_id;



        $dyeingProduction = SubDyeingProduction::query()
        ->with([
            'subDyeingProductionDetails.color',
            'subDyeingBatch',
            'supplier',
        ]);
        if ($formDate && $toDate) {
            $dyeingProduction = $dyeingProduction
            ->when($factory_id, function (Builder $query) use ($factory_id) {
                $query->where('factory_id', $factory_id);
            })
            ->when($order_id, function (Builder $query) use ($order_id) {
                $query->where('order_id', $order_id);
            })
            ->when($batch_id, function (Builder $query) use ($batch_id) {
                $query->where('batch_id', $batch_id);
            })
            ->when($supplier_id, function (Builder $query) use ($supplier_id) {
                $query->where('supplier_id', $supplier_id);
            })
            ->whereBetween('production_date', [$formDate, $toDate])
            ->get();
        } else {
            $dyeingProduction = $dyeingProduction->where('production_date', $todayDate)
            ->get();
        }

        return Excel::download(new DyeingProductionDailyReportExport($dyeingProduction), 'dyeing_production_daily_report.xlsx');
    }
}
