<?php

namespace SkylarkSoft\Cuttingdroplets\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use SkylarkSoft\Cuttingdroplets\Models\BundleCard;
use Session;


class CuttingQcReportController extends Controller
{
    
    public function orderWiseQcReport(Request $request)
    {
    	$bundle_info = null;
    	if($request->isMethod('post') && $request->has('barcode') && substr($request->barcode, -2) == 'RP'){
            $bundle = BundleCard::where('rp_barcode', $request->barcode)->first();
            if(isset($bundle) > 0 && $bundle->qc_status == 0){
            	return view('cuttingdroplets::forms.cutting-qc.report-qc-scan-rejection')->with('bundle', $bundle);
            }else{
            	Session::flash('error', 'Please scan valid barcode');
                $bundle_info = BundleCard::where(['bundle_card_generation_detail_id' => Session::get('challan_id'), 'qc_status' => 1])->with('details')->get();
            }
    	}elseif(Session::has('challan_id')){
           $bundle_info = BundleCard::where(['bundle_card_generation_detail_id' => Session::get('challan_id'), 'qc_status' => 1])->with('details')->get();           
    	}

    	return view('cuttingdroplets::forms.cutting-qc.cutting-qc-scan')->with('bundle_info', $bundle_info);    	   	
    }

    public function cuttingQcRejectionPost(Request $request)
    {    
        $request->request->add(['qc_status' => ACTIVE]);
        $total_rejection = $request->fabric_holes_small + $request->fabric_holes_large + $request->end_out + $request->dirty_spot + $request->oil_spot + $request->colour_spot + $request->lycra_missing + $request->missing_yarn + $request->crease_mark + $request->others;

        $request->request->add(['total_rejection' => $total_rejection]);
       
        if(BundleCard::findOrFail($request->id)->update($request->all())){
        	Session::flash('success', S_UPDATE_MSG);
        }else{
        	Session::flash('error', E_UPDATE_MSG);
        }
        Session::put('challan_id', $request->challan_id);
        return redirect('cutting-qc-scan');
    }
}
