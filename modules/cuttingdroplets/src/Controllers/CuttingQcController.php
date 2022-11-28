<?php

namespace SkylarkSoft\GoRMG\Cuttingdroplets\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use SkylarkSoft\Cuttingdroplets\Models\BundleCard;
use SkylarkSoft\Ordertracking\Models\Buyer;
use SkylarkSoft\Ordertracking\Models\Order;
use SkylarkSoft\Cuttingdroplets\Models\BundleCardGenerationDetail;
use Session;
use Carbon\Carbon;


class CuttingQcController extends Controller
{

    public function cuttingQcScan(Request $request)
    {
        $bundle_info = null;
        $qc_challan = $this->getChallanNo();
        if ($request->isMethod('post') && $request->has('barcode')) {
            $bundle = BundleCard::where('id', substr($request->barcode, 1, 9))->first();
            if ($bundle && $bundle->qc_status == 0 && substr($request->barcode, 0, 1)) {
                return view('cuttingdroplets::forms.cutting_qc_scan_rejection', [
                    'bundle' => $bundle,
                    'qc_challan' => $qc_challan
                ]);
            }else {
                //Session::flash('error', 'Please scan valid barcode');
            }
        }

        $bundle_info = BundleCard::where(['cutting_qc_challan_no' => $qc_challan, 'cutting_qc_challan_status' => false, 'qc_status' => 1])
            ->with('details','order','color')
            ->get();

        return view('cuttingdroplets::forms.cutting_qc_scan', [
            'bundle_info' => $bundle_info,
            'qc_challan' => $qc_challan
        ]);
    }

    public function getChallanNo()
    {
        $qc_challan_no = BundleCard::where('cutting_qc_challan_status', 0)->first();

        if ($qc_challan_no && $qc_challan_no->cutting_qc_challan_no) {
            $qc_challan = $qc_challan_no->cutting_qc_challan_no;
        } else {
            $qc_challan = BundleCard::max('cutting_qc_challan_no') + 1;
        }
        return $qc_challan;
    }

    public function closeChallan($cutting_qc_challan_no)
    {
        BundleCard::where('cutting_qc_challan_no', $cutting_qc_challan_no)
            ->update(['cutting_qc_challan_status' => true]);
        return redirect('/cutting-qc-scan');
    }

    public function cuttingQcRejectionPost(Request $request)
    {
        $request->request->add(['qc_status' => ACTIVE]);
        $total_rejection = $request->fabric_holes_small + $request->fabric_holes_large + $request->end_out + $request->dirty_spot + $request->oil_spot + $request->colour_spot + $request->lycra_missing + $request->missing_yarn + $request->crease_mark + $request->others;

        $request->request->add(['total_rejection' => $total_rejection]);

        if (BundleCard::findOrFail($request->id)->update($request->all())) {
            Session::flash('success', S_UPDATE_MSG);
        } else {
            Session::flash('error', E_UPDATE_MSG);
        }
        // Session::put('challan_id', $request->challan_id);
        return redirect('cutting-qc-scan');
    }

    public function orderWiseQcReport(Request $request)
    {
        $order_qty = $this->orderWiseQcReportData();

        return view('reports.cutting-qc-module.order-wise-qc-report')->with('order_qty', $order_qty);
    }

    public function orderWiseQcReportData()
    {
        return Order::with([
            'buyer',
            'style',
            'todaysCutting'
        ])
        ->orderBy('buyer_id')
        ->paginate(20);
    }

    public function orderWiseQcReportDownload($type)
    {
        $data['order_qty'] = $this->orderWiseQcReportData();
        if ($type == 'pdf') {
            $pdf = \PDF::loadView('reports.downloads.cutting-module.pdf.order-wise-cutting-qc-report-download', $data, [], [
                'format' => 'A4-L'
            ]);
            return $pdf->download('bundlecard-scan-check-report.pdf');
        } else {
            \Excel::create('Order Wise QC Report', function ($excel) use ($data) {
                $excel->sheet('Order Wise QC Report sheet', function ($sheet) use ($data) {
                    $sheet->loadView('reports.downloads.cutting-module.excels.order-wise-cutting-qc-report-download', $data);
                });
            })->export('xls');
        }
    }

    public function buyerWiseQcReport()
    {
        $buyers = Buyer::pluck('name', 'id')->all();
        return view('reports.cutting-qc-module.buyer-wise-qc-report')->with('buyers', $buyers);
    }

    public function buyerWiseQcReportView($buyer_id)
    {
        $result_data = [];
        $orders = Order::where('buyer_id', $buyer_id)
            ->get();
        foreach ($orders as $order) {
            $gen_details = BundleCardGenerationDetail::with('bundleCards')
                ->where('buyer_id', $buyer_id)
                ->where('order_id', $order->id)
                ->where('is_regenerated', 0)
                ->get()
                ->groupBy('color_id');

            $cutting_qty = 0;
            foreach ($gen_details as $key => $color_wise) {
                $result_data[$key]['color'] = $color_wise->first()->color->name;
                $result_data[$key]['order'] = $order->order_no;
                $order_qty = $color_wise->first()->color->color_details->sum('quantity');
                $result_data[$key]['order_qty'] = $order_qty;

                $cutting_qty = 0;
                $todays_qty = 0;
                $rejection = 0;
                foreach ($color_wise as $details) {
                    foreach ($details->bundleCards as $bundle) {
                        if ($bundle->status == 1) {
                            $genDate = $bundle->updated_at->toDateString();
                            $today = Carbon::today()->toDateString();
                            if ($genDate == $today) {
                                $todays_qty += $bundle->quantity;
                            }
                            $cutting_qty += $bundle->quantity;
                            $rejection += $bundle->total_rejection;
                        }
                    }
                }
                $result_data[$key]['cutting_qty'] = $cutting_qty;
                $result_data[$key]['left_qty'] = $order_qty - $cutting_qty;
                $result_data[$key]['todays_qty'] = $todays_qty;
                $result_data[$key]['rejection'] = $rejection;

                $extra = 0;
                if ($order_qty > 0 && $cutting_qty > 0) {
                    $extra = (($cutting_qty - $order_qty) * 100) / $order_qty;
                }
                $result_data[$key]['extra'] = $extra > 0 ? number_format($extra, 2) : 0;
            }

        }
        return $result_data;
    }

    public function MonthWiseQcReport(Request $request)
    {
        $report_data = null;
        if ($request->isMethod('post')) {
            $report_data = BundleCard::whereBetween('updated_at', [$request->from_date, $request->to_date])->groupBy('bundle_card_generation_detail_id')->with('details')->get(['bundle_cards.bundle_card_generation_detail_id']);
        }
        return view('reports.cutting-qc-module.month-wise-qc-report', [
            'report_data' => $report_data,
            'from_date' => $request->from_date,
            'to_date' => $request->to_date,
        ]);
    }
}
