<?php

namespace SkylarkSoft\GoRMG\Cuttingdroplets\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use SkylarkSoft\GoRMG\Cuttingdroplets\Models\BundleCard;
use Illuminate\Support\Facades\Session;
use SkylarkSoft\GoRMG\Cuttingdroplets\Actions\UpdateSingleBundleCuttingQtyInReports;

class CuttingScanController extends Controller
{
    public function cuttingScan(Request $request)
    {
        $challan_no = $this->getChallanNo();

        $bundle_info = BundleCard::where(['cutting_challan_no' =>  $challan_no, 'cutting_challan_status' => 0, 'status' => 1])
            ->with('details', 'order', 'color')
            ->get();

        return view('cuttingdroplets::forms.cutting_scan', [
            'bundle_info' => $bundle_info,
            'challan_no' => $challan_no
        ]);
    }

    public function cuttingScanPost(Request $request)
    {
        $request->validate([
            'barcode' => 'required',
            'challan_no' => 'required'
        ]);
        try {
            $bundleCardId = ltrim(substr($request->barcode, 1, 9), 0);
            $challanNo = $request->challan_no;
            // checked already scanned or not
            $is_valid = BundleCard::where('id', $bundleCardId);
            if ($is_valid->count() == 1) {
                $is_exist = $is_valid->first()->status;
                if (!$is_exist) {
                    DB::transaction(function () use($bundleCardId, $challanNo) {
                        BundleCard::where('id', $bundleCardId)->update([
                            'cutting_challan_no' => $challanNo, 
                            'status' => 1,
                            'cutting_date' => \operationDate()
                        ]);
                        $bundleCard = BundleCard::where('id', $bundleCardId)->get();
                        (new UpdateSingleBundleCuttingQtyInReports)->setBundleCards($bundleCard)->handle();
                    });
                } else {
                    Session::flash('error', 'Already scanned this bundle');
                }
            } else {
                Session::flash('error', 'Please scan valid barcode');
            }
        } catch (Exception $e) {
            Log::error($e->getMessage());
            Session::flash('error', \SOMETHING_WENT_WRONG);
        }

        return redirect('/cutting-scan');
    }

    public function getChallanNo()
    {
        $challan = BundleCard::where('cutting_challan_no', '!=', NULL)->where('cutting_challan_status', 0)->first();
        if ($challan) {
            $challan_no = $challan->cutting_challan_no;
        } else {
            $challan_no = time();
        }
        return $challan_no;
    }

    public function closeCuttingScan($cutting_challan_no)
    {
        BundleCard::where('cutting_challan_no', $cutting_challan_no)
            ->update(['cutting_challan_status' => true]);

        return redirect('/cutting-scan');
    }
}
