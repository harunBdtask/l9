<?php

namespace SkylarkSoft\GoRMG\Sewingdroplets\Controllers;

use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Cache;
use SkylarkSoft\GoRMG\Cuttingdroplets\Models\TotalProductionReport;
use SkylarkSoft\GoRMG\Cuttingdroplets\Services\ColorSizeSummaryReportService;
use SkylarkSoft\GoRMG\Inputdroplets\Models\DateWiseSewingProductionReport;
use SkylarkSoft\GoRMG\Inputdroplets\Models\FinishingProductionReport;
use SkylarkSoft\GoRMG\Sewingdroplets\Models\HourlySewingProductionReport;
use SkylarkSoft\GoRMG\Cuttingdroplets\Models\DateAndColorWiseProduction;
use SkylarkSoft\GoRMG\Sewingdroplets\Models\Sewingoutput;
use SkylarkSoft\GoRMG\Inputdroplets\Models\CuttingInventory;
use SkylarkSoft\GoRMG\Cuttingdroplets\Models\BundleCard;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\DB;
use SkylarkSoft\GoRMG\Inputdroplets\Models\LineSizeWiseSewingReport;
use SkylarkSoft\GoRMG\Sewingdroplets\Services\SewingOutputCacheKeyService;
use SkylarkSoft\GoRMG\Sewingdroplets\Services\SewingOutputScanHandleService;

class SewingOutputScanController extends Controller
{

    public function sewingOutputScanForm()
    {
        $output_challan_no = $this->getChallanNo();
        $output_bundles = $this->getCurrentChallanData($output_challan_no);

        return view('sewingdroplets::forms.sewing_ouput_scan', [
            'output_challan_no' => $output_challan_no,
            'output_bundles' => $output_bundles
        ]);
    }

    public function getChallanNo()
    {
        $cacheKey = (new SewingOutputCacheKeyService)->getChallanNoCacheKey();

        return Cache::remember($cacheKey, 86400, function () {
            $challan = DB::table('sewingoutputs')
                ->select('output_challan_no')
                ->where('user_id', userId())
                ->whereDate('created_at', date('Y-m-d'))
                ->first();

            return $challan->output_challan_no ?? userId() . time();
        });
    }

    public function getCurrentChallanData($output_challan_no)
    {
        $cacheKey = (new SewingOutputCacheKeyService)->getChallanBundlesCacheKey();

        return Cache::remember($cacheKey, 86400, function () use($output_challan_no) {
            return Sewingoutput::getChallanData($output_challan_no)
                ->map(function ($bundle) {
                    return [
                        'bundle_card_id' => $bundle->bundle_card_id,
                        'line_no' => $bundle->line->line_no ?? $this->getBundleDetailsData($bundle->details, 'line_no'),
                        'buyer' => $this->getBundleDetailsData($bundle->details, 'buyer'),
                        'style_name' => $this->getBundleDetailsData($bundle->details, 'style_name'),
                        'po_no' => $this->getBundleDetailsData($bundle->details, 'po_no'),
                        'color' => $this->getBundleDetailsData($bundle->details, 'color'),
                        'size' => $this->getBundleDetailsData($bundle->details, 'size'),
                        'bundle_no' => $this->getBundleDetailsData($bundle->details, 'bundle_no'),
                        'sewing_qty' => $this->getBundleDetailsData($bundle->details, 'sewing_qty', 0),
                    ];
                });
        });
    }

    private function getBundleDetailsData($bundleDetails, $key, $defaultValue = '')
    {
        return array_key_exists($key, $bundleDetails) ? $bundleDetails[$key] : $defaultValue;
    }

    public function sewingOutputScanPost(Request $request)
    {
        $response = (new SewingOutputScanHandleService)->setRequest($request)->handle();
        
        return response()->json($response);
    }

    public function sewingChallanClose($output_challan_no)
    {
        Sewingoutput::where('output_challan_no', $output_challan_no)->update(['status' => ACTIVE]);
        (new SewingOutputCacheKeyService)->removeCache();

        return redirect('sewing-output-scan');
    }

    public function sewingRejection($bundle_id)
    {
        $sewing_output = Sewingoutput::where('bundle_card_id', $bundle_id)->first();

        return view('sewingdroplets::forms.sewing_rejection')
            ->with('sewing_output', $sewing_output);
    }

    public function sewingRejectionPost(Request $request)
    {
        $request->validate([
            'sewing_rejection' => 'required|numeric|min:0'
        ]);

        try {

            DB::beginTransaction();
            $bundlecard = BundleCard::with('sewingoutput', 'details:id,is_manual')
                ->where('id', $request->id)
                ->first();

            $sewingOutput = $bundlecard->sewingoutput;
            $sewingRejection = $request->sewing_rejection;
            $bundleQty = $bundlecard->quantity;
            $bundleAggregatedRejection = $bundlecard->total_rejection
                + $bundlecard->print_rejection
                + $bundlecard->embroidary_rejection
                + $sewingRejection;

            if ($bundleQty <= $bundleAggregatedRejection) {
                return response()->json([
                    'status' => 500,
                    'message' => 'Sorry!! Rejection must be less than bundlecard quantity',
                    'details' => null,
                    'error' => null
                ]);
            }
            $output_qty = $bundlecard->quantity
                - $bundlecard->total_rejection
                - $bundlecard->print_rejection
                - $bundlecard->embroidary_rejection
                - $sewingRejection;
            $details = [
                'bundle_card_id' => $request->id,
                'line_no' => $sewingOutput->line->line_no,
                'buyer' => $bundlecard->buyer->name,
                'style_name' => $bundlecard->order->style_name,
                'po_no' => $bundlecard->purchaseOrder->po_no,
                'color' => $bundlecard->color->name,
                'size' => $bundlecard->size->name,
                'bundle_no' => $bundlecard->details->is_manual == 1 ? $bundlecard->size_wise_bundle_no : ($bundlecard->{getbundleCardSerial()} ?? $bundlecard->bundle_no ?? ''),
                'sewing_qty' => $output_qty,
            ];

            DB::table('bundle_cards')
                ->where('id', $request->id)
                ->update(['sewing_rejection' => $request->sewing_rejection]);
            DB::table('sewingoutputs')
                ->where('bundle_card_id', $request->id)
                ->update([
                    'details' => json_encode($details)
                ]);
            // Update or Add total_production_reports table
            $this->updateTotalProductionReportForSewingRejection($bundlecard, $sewingRejection);
            $production_date = $sewingOutput->updated_at->toDateString();
            $this->updateDateWiseSewingProductionReportForSewingRejection($sewingOutput, $bundlecard, $sewingRejection, $production_date);
            $this->updateHourlySewingProductionReportForSewingRejection($bundlecard, $sewingOutput, $sewingRejection, $production_date);
            $this->updateColorAndDateWiseProductionReportForSewingRejection($bundlecard, $sewingRejection, $production_date);
            $this->updateFinishingProductionReportForSewingRejection($sewingOutput, $bundlecard, $sewingRejection, $production_date);
            $this->updateLineSizeWiseSewingReportForSewingRejection($sewingOutput, $bundlecard, $sewingRejection, $production_date);

            (new ColorSizeSummaryReportService())->make($bundlecard)->sewingOutputRejection($sewingRejection)->saveOrUpdate();
            (new SewingOutputCacheKeyService)->updateCacheRejectionQty($request->id, $details);

            Session::flash('success', S_UPDATE_MSG);
            DB::commit();
            return response()->json([
                'status' => 200,
                'message' => S_UPDATE_MSG,
                'details' => $details ?? null,
                'error' => null
            ]);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json([
                'status' => 500,
                'message' => 'Sorry!! Something went wrong!',
                'details' => $details ?? null,
                'error' => $e->getMessage()
            ]);
        }
    }

    private function updateTotalProductionReportForSewingRejection($bundlecard, $sewingRejection)
    {
        $sewingReport = TotalProductionReport::where([
            'order_id' => $bundlecard->order_id,
            'garments_item_id' => $bundlecard->garments_item_id,
            'purchase_order_id' => $bundlecard->purchase_order_id,
            'color_id' => $bundlecard->color_id
        ])->first();

        if (!$sewingReport) {
            $sewingReport = new TotalProductionReport();

            $sewingReport->buyer_id = $bundlecard->buyer_id;
            $sewingReport->order_id = $bundlecard->order_id;
            $sewingReport->garments_item_id = $bundlecard->garments_item_id;
            $sewingReport->purchase_order_id = $bundlecard->purchase_order_id;
            $sewingReport->color_id = $bundlecard->color_id;
        }

        $sewingReport->todays_sewing_output -= $sewingRejection;
        $sewingReport->total_sewing_output -= $sewingRejection;
        $sewingReport->todays_sewing_rejection += $sewingRejection;
        $sewingReport->total_sewing_rejection += $sewingRejection;
        $sewingReport->save();
        return true;
    }

    private function updateFinishingProductionReportForSewingRejection($sewingOutput, $bundlecard, $sewingRejection, $production_date)
    {
        $purchaseOrderId = $bundlecard->purchase_order_id;
        $colorId = $bundlecard->color_id;
        $floor_id = $sewingOutput->line->floor->id;
        $line_id = $sewingOutput->line_id;

        $finishingProductionReport = FinishingProductionReport::where([
            'floor_id' => $floor_id,
            'line_id' => $line_id,
            'purchase_order_id' => $purchaseOrderId,
            'color_id' => $colorId,
            'production_date' => $production_date,
        ])->first();

        if ($finishingProductionReport) {
            if ($finishingProductionReport->sewing_output >= $sewingRejection) {
                $finishingProductionReport->sewing_output -= $sewingRejection;
            } else {
                $finishingProductionReport->sewing_output = 0;
            }
            $finishingProductionReport->sewing_rejection += $sewingRejection;
            $finishingProductionReport->save();
        }
        return true;
    }

    private function updateLineSizeWiseSewingReportForSewingRejection($sewingOutput, $bundlecard, $sewingRejection, $production_date)
    {
        $purchaseOrderId = $bundlecard->purchase_order_id;
        $colorId = $bundlecard->color_id;
        $sizeId = $bundlecard->size_id;
        $floor_id = $sewingOutput->line->floor->id;
        $line_id = $sewingOutput->line_id;

        $report = LineSizeWiseSewingReport::where([
            'floor_id' => $floor_id,
            'line_id' => $line_id,
            'purchase_order_id' => $purchaseOrderId,
            'color_id' => $colorId,
            'size_id' => $sizeId,
            'production_date' => $production_date,
        ])->first();

        if ($report) {
            if ($report->sewing_output >= $sewingRejection) {
                $report->sewing_output -= $sewingRejection;
            } else {
                $report->sewing_output = 0;
            }
            $report->sewing_rejection += $sewingRejection;
            $report->save();
        }
        return true;
    }

    public function updateColorAndDateWiseProductionReportForSewingRejection($bundlecard, $sewingRejection, $production_date)
    {
        $date = date('Y-m-d');
        $dateAndColorWiseProduction = DateAndColorWiseProduction::where([
            'purchase_order_id' => $bundlecard->purchase_order_id,
            'color_id' => $bundlecard->color_id,
            'production_date' => $production_date,
        ])->first();

        $dateAndColorWiseProduction->sewing_output_qty -= $sewingRejection;
        $dateAndColorWiseProduction->sewing_rejection_qty += $sewingRejection;
        $dateAndColorWiseProduction->save();
    }

    public function updateDateWiseSewingProductionReportForSewingRejection($sewingOutput, $bundlecard, $rejection_qty, $production_date)
    {
        //$sewing_date = date('Y-m-d');
        $orderId = $bundlecard->purchase_order_id;
        $colorId = $bundlecard->color_id;
        $floor_id = $sewingOutput->line->floor->id;
        $line_id = $sewingOutput->line_id;

        $date_wise_sewing_production_report = DateWiseSewingProductionReport::where([
            'floor_id' => $floor_id,
            'line_id' => $line_id,
            'sewing_date' => $production_date
        ])->first();

        if ($date_wise_sewing_production_report) {
            $sewing_details_existing_data = $date_wise_sewing_production_report->sewing_details;
            $total_sewing_input = $date_wise_sewing_production_report->total_sewing_input;
            $total_sewing_output = $date_wise_sewing_production_report->total_sewing_output - $rejection_qty;
            $total_sewing_rejection = $date_wise_sewing_production_report->total_sewing_rejection + $rejection_qty;

            foreach ($sewing_details_existing_data as $key => $sewing_detail) {
                if ($sewing_detail['purchase_order_id'] == $orderId && $sewing_detail['color_id'] == $colorId) {
                    $sewing_details_existing_data[$key] = [
                        'buyer_id' => $sewing_detail['buyer_id'],
                        'order_id' => $sewing_detail['order_id'],
                        'purchase_order_id' => $sewing_detail['purchase_order_id'],
                        'color_id' => $sewing_detail['color_id'],
                        'sewing_input' => $sewing_detail['sewing_input'] ?? 0,
                        'sewing_output' => $sewing_detail['sewing_output'] - $rejection_qty,
                        'sewing_rejection' => $sewing_detail['sewing_rejection'] + $rejection_qty
                    ];
                    break;
                }
            }
            $date_wise_sewing_production_report->total_sewing_input = $total_sewing_input ?? 0;
            $date_wise_sewing_production_report->total_sewing_output = $total_sewing_output ?? 0;
            $date_wise_sewing_production_report->total_sewing_rejection = $total_sewing_rejection ?? 0;
            $date_wise_sewing_production_report->sewing_details = $sewing_details_existing_data;
            $date_wise_sewing_production_report->save();
        }
        return true;
    }

    public function updateHourlySewingProductionReportForSewingRejection($bundlecard, $sewingoutput, $sewing_rejection, $production_date)
    {
        // start hourly prodcution report observer report
        $order = $sewingoutput->purchaseOrder;
        $current_hour = (int)date('H');
        if ($current_hour == 13) {
            $current_hour = 12;
        }

        $column_name = 'hour_' . $current_hour;

        $hourlyReport = HourlySewingProductionReport::where([
            'production_date' => $production_date,
            'line_id' => $sewingoutput->line_id,
            'order_id' => $bundlecard->order_id,
            'garments_item_id' => $bundlecard->garments_item_id,
            'purchase_order_id' => $sewingoutput->purchase_order_id,
            'color_id' => $sewingoutput->color_id
        ])->first();

        if (!$hourlyReport) {

            $hourlyReport = new HourlySewingProductionReport();
            $hourlyReport->production_date = $production_date;
            $hourlyReport->floor_id = $sewingoutput->line->floor_id;
            $hourlyReport->line_id = $sewingoutput->line_id;
            $hourlyReport->buyer_id = $sewingoutput->purchaseOrder->buyer_id;
            $hourlyReport->order_id = $sewingoutput->purchaseOrder->order_id;
            $hourlyReport->garments_item_id = $bundlecard->garments_item_id;
            $hourlyReport->purchase_order_id = $sewingoutput->purchase_order_id;
            $hourlyReport->color_id = $sewingoutput->color_id;
        }

        $hourlyReport->$column_name -= $sewing_rejection;
        $hourlyReport->sewing_rejection += $sewing_rejection;
        $hourlyReport->factory_id = $order->factory_id;
        $hourlyReport->save();

        return true;
    }

    public function bundleWiseQc(Request $request)
    {
        if ($request->has('bundlecard')) {
            $bundleCardId = substr($request->bundlecard, 1, 9);
            $bundleInfo = BundleCard::where('id', $bundleCardId)
                ->first();

            if (!$bundleInfo) {
                Session::flash('error', 'Please enter valid bundlecard');
            }
        }

        return view('sewingdroplets::forms.get_bundle_wise_qc', [
            'bundlecard' => $request->bundlecard ?? '',
            'bundleInfo' => $bundleInfo ?? null
        ]);
    }

    public function sewingoutputChallanList(Request $request)
    {
        /* $request->validate([
             'from_date' => 'required|date|before_or_equal:to_date',
             'to_date' => 'required|date|after_or_equal:from_date'
         ]);*/

        $from_date = $request->from_date ?? date('Y-m-d');
        $to_date = $request->to_date ?? date('Y-m-d');

        $frmDate = Carbon::parse($from_date);
        $toDate = Carbon::parse($to_date);
        $diff = $frmDate->diffInDays($toDate);

        if ($diff > 31) {
            Session::flash('error', 'Please enter maximum one month date range');
            return redirect()->back();
        }

        $reportData = $this->getSewingChallanList($from_date, $to_date);

        return view('sewingdroplets::pages.sewingoutput_challan_list', [
            'from_date' => $request->from_date,
            'to_date' => $request->to_date,
            'reportData' => $reportData
        ]);
    }

    public function getSewingChallanList($from_date, $to_date)
    {
        $report_data = Sewingoutput::with('user:id,first_name,last_name,screen_name,email')
            ->whereDate('created_at', '>=', $from_date)
            ->whereDate('created_at', '<=', $to_date)
            ->selectRaw('DATE_FORMAT(created_at, "%Y-%m-%d") as createDate, user_id, output_challan_no')
            ->get()
            ->groupBy('createDate');

        return $report_data;
    }

    public function viewSewingoutputChallan($output_challan_no)
    {
        $outputBundleCardIds = Sewingoutput::where('output_challan_no', $output_challan_no)
            ->pluck('bundle_card_id')
            ->all();

        $challanData = BundleCard::with([
            'buyer:id,name',
            'order:id,style_name',
            'purchaseOrder:id,po_no',
            'color:id,name',
            'size:id,name'
        ])
            ->whereIn('id', $outputBundleCardIds)
            ->select(
                'id',
                'buyer_id',
                'order_id',
                'purchase_order_id',
                'color_id',
                'size_id',
                'quantity',
                'total_rejection',
                'print_rejection',
                'embroidary_rejection',
                'sewing_rejection',
                'sewing_output_date'
            )->get();

        return view('sewingdroplets::pages.view_sewingoutput_challan', [
            'challanData' => $challanData
        ]);
    }
}
