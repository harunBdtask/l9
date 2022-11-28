<?php

namespace SkylarkSoft\Cuttingdroplets\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use SkylarkSoft\Cuttingdroplets\Models\BundleCard;
use SkylarkSoft\Ordertracking\Models\Buyer;
use SkylarkSoft\Ordertracking\Models\Order;
use SkylarkSoft\Ordertracking\Models\OrderDetail;
use SkylarkSoft\Cuttingdroplets\Models\BundleCardGenerationDetail;
use DB;
use Carbon\Carbon;
use PDF;
use Excel;

class DownloadCuttingReportController extends Controller
{
    
    public function allOrdersCuttingReport($pdf)
    {
        $order_qty = BundleCardGenerationDetail::with('bundleCards','buyer','order')->where('is_regenerated', 0)
            ->get()->groupBy('order_id')
            ->map(function($items){
                $genDetail = $items->first();
                $qty = $items->map(function($item) {
                    $genDate = $item->updated_at->toDateString();
                    $today = Carbon::today()->toDateString();

                    return [
                        'todays_cutting' => ($genDate == $today ? $item->bundleCards->where('status', 1)->sum('quantity') : 0),
                        'cutting_qtys' => $item->bundleCards->where('status', 1)->sum('quantity')
                    ];
                });  

                $extra = (($qty->sum('cutting_qtys') - $genDetail->order->total_quantity) * 100) / $genDetail->order->total_quantity;                         

                return [
                    'buyer' => $genDetail->buyer->name,
                    'order' => $genDetail->order->order_no,                    
                    'order_qty' => $genDetail->order->total_quantity,
                    'cutting_qty' => $qty->sum('cutting_qtys'),
                    'todays_cutting' => $qty->sum('todays_cutting'),
                    'left_qt' => $genDetail->order->total_quantity - $qty->sum('cutting_qtys'),
                    'extra' => $extra > 0 ? number_format($extra, 2) : 0
                ];
            });
    	$pdf = PDF::loadView('reports.downloads.all-orders-cutting-report', compact('order_qty'));
    	return $pdf->download('all_orders.pdf');
    }

    public function allOrdersCuttingReportExl()
    {
    	$order_qty = BundleCardGenerationDetail::with('bundleCards','buyer','order')->where('is_regenerated', 0)
            ->get()->groupBy('order_id')
            ->map(function($items){
                $genDetail = $items->first();
                $qty = $items->map(function($item) {
                    $genDate = $item->updated_at->toDateString();
                    $today = Carbon::today()->toDateString();

                    return [
                        'todays_cutting' => ($genDate == $today ? $item->bundleCards->where('status', 1)->sum('quantity') : 0),
                        'cutting_qtys' => $item->bundleCards->where('status', 1)->sum('quantity')
                    ];
                });  

                $extra = (($qty->sum('cutting_qtys') - $genDetail->order->total_quantity) * 100) / $genDetail->order->total_quantity;                         

                return [
                    'buyer' => $genDetail->buyer->name,
                    'order' => $genDetail->order->order_no,                    
                    'order_qty' => $genDetail->order->total_quantity,
                    'cutting_qty' => $qty->sum('cutting_qtys'),
                    'todays_cutting' => $qty->sum('todays_cutting'),
                    'left_qt' => $genDetail->order->total_quantity - $qty->sum('cutting_qtys'),
                    'extra' => $extra > 0 ? number_format($extra, 2) : 0
                ];
            });
         
            //$provinces = Province::with('country')->orderBy('name', 'ASC')->get();
			Excel::create('reportTitle', function ($excel) use ($order_qty) {
                $excel->sheet('reportTitle', function ($sheet) use (&$order_qty) {
                    $sheet->loadView('reports.downloads.all-orders-cutting-report', compact('order_qty'));
                      //  ->withProvinces($order_qty) ;
                });
            })->download('xls');
    }
}
