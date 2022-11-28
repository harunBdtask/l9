<?php

namespace SkylarkSoft\GoRMG\Dyeing\Controllers\Reports\DyeingProductionDailyReport;

use PDF;
use Excel;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Database\Eloquent\Builder;
use SkylarkSoft\GoRMG\Dyeing\PackageConst;
use SkylarkSoft\GoRMG\SystemSettings\Models\Buyer;
use SkylarkSoft\GoRMG\SystemSettings\Models\Factory;
use SkylarkSoft\GoRMG\Dyeing\Exports\DyeingProductionDailyReportExport;
use SkylarkSoft\GoRMG\Dyeing\Models\TextileModels\DyeingBatch\DyeingBatch;
use SkylarkSoft\GoRMG\Dyeing\Models\TextileModels\TextileOrders\TextileOrder;
use SkylarkSoft\GoRMG\Dyeing\Models\TextileModels\DyeingProduction\DyeingProduction;

class DyeingProductionDailyReportController extends Controller
{
    public function view()
    {
        $todayDate = Carbon::now()->format('Y-m-d');
        $dyeingProduction = DyeingProduction::query()
                            ->with([
                                'dyeingProductionDetails.color',
                                'dyeingBatch',
                                'buyer'
                            ])
                            ->where('production_date',$todayDate)
                            ->get();
        $factories = Factory::query()->pluck('factory_name', 'id');
        $batches = DyeingBatch::query()->pluck('batch_no','id')
                                  ->prepend('Select','');
        $orders = TextileOrder::query()->pluck('fabric_sales_order_no', 'id')
                                  ->prepend('Select','');
        $buyers = Buyer::query()->pluck('name', 'id')
                                  ->prepend('Select','');
         //dd($dyeingProduction);
        return view(PackageConst::VIEW_PATH.'reports.dyeing-production-daily-report.dyeing-production-daily-report',[
            'dyeingProduction' => $dyeingProduction,
            'factories' => $factories,
            'batches' => $batches,
            'orders' => $orders,
            'buyers' => $buyers
        ]);
    }


    public function getReport(Request $request)
    {
        $factory_id = $request->factory_id;
        $formDate = $request->form_date ? Carbon::make($request->form_date)->format('Y-m-d') : date('Y-m-d');
        $toDate = $request->to_date ? Carbon::make($request->to_date)->format('Y-m-d') : date('Y-m-d');
        $order_id = $request->order_id;
        $batch_id = $request->batch_id;
        $buyer_id = $request->buyer_id;
        //dd($toDate);
        $dyeingProduction = DyeingProduction::query()
        ->with([
            'dyeingProductionDetails.dyeingProduction',
            'dyeingBatch'
        ])
        ->when($factory_id, function (Builder $query) use ($factory_id) {
            $query->where('factory_id',$factory_id);
        })
        ->when($order_id, function (Builder $query) use ($order_id) {
            $query->where('dyeing_order_id', $order_id);
        })
        ->when($batch_id, function (Builder $query) use ($batch_id) {
            $query->where('dyeing_batch_id', $batch_id);
        })
        ->when($buyer_id, function (Builder $query) use ($buyer_id) {
            $query->where('buyer_id', $buyer_id);
        })
        ->whereBetween('production_date', [$formDate, $toDate])
        ->get();

        return view(PackageConst::VIEW_PATH.'reports.dyeing-production-daily-report.dyeing-production-daily-report-table',[
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
        $buyer_id = $request->buyer_id;

        

        $dyeingProduction = DyeingProduction::query()
        ->with([
            'dyeingProductionDetails.color',
            'dyeingBatch',
            'buyer'
        ]);
        if($formDate && $toDate)
        {
            $dyeingProduction = $dyeingProduction 
            ->when($factory_id, function (Builder $query) use ($factory_id) {
                $query->where('factory_id',$factory_id);
            })
            ->when($order_id, function (Builder $query) use ($order_id) {
                $query->where('order_id', $order_id);
            })
            ->when($batch_id, function (Builder $query) use ($batch_id) {
                $query->where('batch_id', $batch_id);
            })
            ->when($buyer_id, function (Builder $query) use ($buyer_id) {
                $query->where('buyer_id', $buyer_id);
            })
            ->whereBetween('production_date', [$formDate, $toDate])
            ->get();
        }
        else
        {
            $dyeingProduction = $dyeingProduction->where('production_date',$todayDate)
            ->get();
        }

        $pdf = PDF::setOption('enable-local-file-access', true)
        ->loadView('dyeing::reports.dyeing-production-daily-report.dyeing-production-daily-report-pdf', [
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
        $buyer_id = $request->buyer_id;

        

        $dyeingProduction = DyeingProduction::query()
        ->with([
            'dyeingProductionDetails.color',
            'dyeingBatch',
            'buyer'
        ]);
        if($formDate && $toDate)
        {
            $dyeingProduction = $dyeingProduction 
            ->when($factory_id, function (Builder $query) use ($factory_id) {
                $query->where('factory_id',$factory_id);
            })
            ->when($order_id, function (Builder $query) use ($order_id) {
                $query->where('order_id', $order_id);
            })
            ->when($batch_id, function (Builder $query) use ($batch_id) {
                $query->where('batch_id', $batch_id);
            })
            ->when($buyer_id, function (Builder $query) use ($buyer_id) {
                $query->where('buyer_id', $buyer_id);
            })
            ->whereBetween('production_date', [$formDate, $toDate])
            ->get();
        }
        else
        {
            $dyeingProduction = $dyeingProduction->where('production_date',$todayDate)
            ->get();
        }

        return Excel::download(new DyeingProductionDailyReportExport($dyeingProduction), 'dyeing_production_daily_report.xlsx');
    }
}