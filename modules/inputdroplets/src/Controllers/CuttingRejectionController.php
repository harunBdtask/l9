<?php

namespace SkylarkSoft\GoRMG\Inputdroplets\Controllers;

use App\Http\Controllers\Controller;
use Exception;
use Illuminate\Http\Request;
use SkylarkSoft\GoRMG\Cuttingdroplets\Models\BundleCard;
use SkylarkSoft\GoRMG\Cuttingdroplets\Models\DateAndColorWiseProduction;
use SkylarkSoft\GoRMG\Cuttingdroplets\Models\DateTableWiseCutProductionReport;
use SkylarkSoft\GoRMG\Cuttingdroplets\Models\DateWiseCuttingProductionReport;
use SkylarkSoft\GoRMG\Cuttingdroplets\Models\TotalProductionReport;
use SkylarkSoft\GoRMG\Cuttingdroplets\Services\ColorSizeSummaryReportService;
use SkylarkSoft\GoRMG\Inputdroplets\Models\CuttingInventory;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\DB;
use SkylarkSoft\GoRMG\Inputdroplets\Services\PrintRcvInputCacheKeyService;
use SkylarkSoft\GoRMG\Printembrdroplets\Services\PrintSendCacheKeyService;

class CuttingRejectionController extends Controller
{

    public function cuttingRejectionForm()
    {
        $bundle = BundleCard::findOrFail(request('bundeId'));

        return view('inputdroplets::forms.cutting_rejection', [
            'bundle' => $bundle,
            'type' => request('type')
        ]);
    }

    public function cuttingRejectionPost(Request $request)
    {
        $request->validate([
            'bundle_id' => 'required',
            'cutting_rejection' => 'required|numeric|min:0'
        ]);

        try {
            DB::beginTransaction();
            $rejectionQty = $request->cutting_rejection;
            $bundleInfo = DB::table('bundle_cards')->where('id', $request->bundle_id);
            $bundleCard = $bundleInfo->first();
            if ($rejectionQty < $bundleCard->quantity) {
                // table data update
                $bundleInfo->update(['total_rejection' => $rejectionQty]);
                (new ColorSizeSummaryReportService())->make($bundleCard)->cuttingRejection($rejectionQty)->saveOrUpdate();
                $this->updateTotalProductionReport($bundleCard, $rejectionQty);
                $this->updateDateWiseCuttingProduction($bundleCard, $rejectionQty);
                $this->updateDateAndColorWiseProduction($bundleCard, $rejectionQty);
                $this->updateDateTableWiseCutProduction($bundleCard, $rejectionQty);
                Session::flash('success', S_UPDATE_MSG);
                DB::commit();
                // back to previous location according to type
                $redirectionUrl = 'cutting-inventory-scan';
                if ($request->type == 'tag') {
                    $cuttingInventory = CuttingInventory::where([
                        'bundle_card_id' => $request->bundle_id
                    ])->first();
                    $redirectionUrl = 'add-bundle-to-tag?tag-no=' . $cuttingInventory->challan_no;
                } elseif ($request->type == 'print') {
                    (new PrintSendCacheKeyService)->updateChallanBundlesCacheRejectionQty($request->bundle_id, $rejectionQty);
                    $redirectionUrl = 'print-send-scan';
                } else {
                    (new PrintRcvInputCacheKeyService)->setItemStatus(0)->updateChallanBundlesCacheRejectionQty($request->bundle_id, $rejectionQty);
                }
                return redirect($redirectionUrl);
            } else {
                Session::flash('error', 'Sorry!! Rejection must be less than bundlecard quantity');
            }
        } catch (Exception $e) {
            DB::rollBack();
            Session::flash('error', $e->getMessage());
        }

        return redirect()->back();
    }

    private function updateTotalProductionReport($bundleCard, $rejectionQty)
    {
        $cuttingReport = TotalProductionReport::where([
            'order_id' => $bundleCard->order_id,
            'garments_item_id' => $bundleCard->garments_item_id,
            'purchase_order_id' => $bundleCard->purchase_order_id,
            'color_id' => $bundleCard->color_id
        ])->first();

        if (!$cuttingReport) {
            $cuttingReport = new TotalProductionReport();

            $cuttingReport->buyer_id = $bundleCard->buyer_id;
            $cuttingReport->order_id = $bundleCard->order_id;
            $cuttingReport->garments_item_id = $bundleCard->garments_item_id;
            $cuttingReport->purchase_order_id = $bundleCard->purchase_order_id;
            $cuttingReport->color_id = $bundleCard->color_id;
        }

        $cuttingReport->todays_cutting_rejection += $rejectionQty;
        $cuttingReport->total_cutting_rejection += $rejectionQty;
        $cuttingReport->save();

        return true;
    }

    private function updateDateWiseCuttingProduction($bundleCard, $rejectionQty)
    {
        // This method update the cutting rejection in date_wise_cutting_production_report table
        $cutting_date = $bundleCard->cutting_date;
        $cutting_floor_id = $bundleCard->cutting_floor_id;
        $cutting_table_id = $bundleCard->cutting_table_id;
        $purchaseOrderId = $bundleCard->purchase_order_id;

        $cutting_details_data = [];
        $cutting_details_data[] = [
            'purchase_order_id' => $purchaseOrderId,
            'color_id' => $bundleCard->color_id,
            'size_id' => $bundleCard->size_id,
            'cutting_qty' => 0,
            'cutting_rejection' => $rejectionQty,
        ];

        $date_wise_cutting_production_report = DateWiseCuttingProductionReport::where([
            'cutting_date' => $cutting_date,
            'cutting_floor_id' => $cutting_floor_id,
            'cutting_table_id' => $cutting_table_id
        ])->first();

        if (!$date_wise_cutting_production_report) {
            $date_wise_cutting_production_report = new DateWiseCuttingProductionReport();
            $date_wise_cutting_production_report->cutting_date = $cutting_date;
            $date_wise_cutting_production_report->cutting_floor_id = $cutting_floor_id;
            $date_wise_cutting_production_report->cutting_table_id = $cutting_table_id;
            $date_wise_cutting_production_report->cutting_details = $cutting_details_data;
            //$date_wise_cutting_production_report->total_cutting = $bundleCard->quantity;
            $date_wise_cutting_production_report->total_rejection = $rejectionQty;
            $date_wise_cutting_production_report->save();
        } else {
            $cutting_details_existing_data = $date_wise_cutting_production_report->cutting_details;
            $total_rejection = $date_wise_cutting_production_report->total_rejection;
            $total_rejection += $rejectionQty;
            foreach ($cutting_details_existing_data as $key => $cutting_detail) {
                $is_detail_exist = 0;

                if (
                    $cutting_detail['purchase_order_id'] == $purchaseOrderId
                    && $cutting_detail['color_id'] == $bundleCard->color_id
                    && $cutting_detail['size_id'] == $bundleCard->size_id
                ) {

                    $is_detail_exist = 1;
                    $cutting_details_existing_data[$key] = [
                        'purchase_order_id' => $purchaseOrderId,
                        'color_id' => $cutting_detail['color_id'],
                        'size_id' => $cutting_detail['size_id'],
                        'cutting_qty' => $cutting_detail['cutting_qty'] ?? 0,
                        'cutting_rejection' => $rejectionQty + $cutting_detail['cutting_rejection'] ?? 0,
                    ];
                    break;
                }
            }
            if ($is_detail_exist == 0) {
                $cutting_details_existing_data = array_merge($cutting_details_existing_data, $cutting_details_data);
            }
            $date_wise_cutting_production_report->total_rejection = $total_rejection ?? 0;
            $date_wise_cutting_production_report->cutting_details = $cutting_details_existing_data;
            $date_wise_cutting_production_report->save();
        }
        return true;
    }

    private function updateDateAndColorWiseProduction($bundleCard, $rejectionQty)
    {
        $cutting_date = $bundleCard->cutting_date;
        $dateAndColorWiseProduction = DateAndColorWiseProduction::where([
            'production_date' => $cutting_date,
            'purchase_order_id' => $bundleCard->purchase_order_id,
            'color_id' => $bundleCard->color_id,
        ])->first();

        if (!$dateAndColorWiseProduction) {
            $dateAndColorWiseProduction = new DateAndColorWiseProduction();

            $dateAndColorWiseProduction->buyer_id = $bundleCard->buyer_id;
            $dateAndColorWiseProduction->order_id = $bundleCard->order_id;
            $dateAndColorWiseProduction->purchase_order_id = $bundleCard->purchase_order_id;
            $dateAndColorWiseProduction->color_id = $bundleCard->color_id;
            $dateAndColorWiseProduction->production_date = $cutting_date;
        }
        $dateAndColorWiseProduction->cutting_rejection_qty += $rejectionQty;
        $dateAndColorWiseProduction->save();

        return true;
    }

    private function updateDateTableWiseCutProduction($bundleCard, $rejectionQty)
    {
        $dateTableWiseCutProductionReports = DateTableWiseCutProductionReport::where([
            'production_date' => $bundleCard->cutting_date,
            'cutting_table_id' => $bundleCard->cutting_table_id,
            'order_id' => $bundleCard->order_id,
            'garments_item_id' => $bundleCard->garments_item_id,
            'purchase_order_id' => $bundleCard->purchase_order_id,
            'color_id' => $bundleCard->color_id,
            'size_id' => $bundleCard->size_id,
        ])->first();

        if (!$dateTableWiseCutProductionReports) {
            $dateTableWiseCutProductionReports = new DateTableWiseCutProductionReport();

            $dateTableWiseCutProductionReports->production_date = $bundleCard->cutting_date;
            $dateTableWiseCutProductionReports->cutting_floor_id = $bundleCard->cutting_floor_id;
            $dateTableWiseCutProductionReports->cutting_table_id = $bundleCard->cutting_table_id;
            $dateTableWiseCutProductionReports->buyer_id = $bundleCard->buyer_id;
            $dateTableWiseCutProductionReports->order_id = $bundleCard->order_id;
            $dateTableWiseCutProductionReports->garments_item_id = $bundleCard->garments_item_id;
            $dateTableWiseCutProductionReports->purchase_order_id = $bundleCard->purchase_order_id;
            $dateTableWiseCutProductionReports->color_id = $bundleCard->color_id;
            $dateTableWiseCutProductionReports->size_id = $bundleCard->size_id;
            $dateTableWiseCutProductionReports->cutting_qty = $bundleCard->quantity;
        }
        $dateTableWiseCutProductionReports->cutting_rejection_qty += $rejectionQty;
        $dateTableWiseCutProductionReports->save();

        return true;
    }
}
