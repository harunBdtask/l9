<?php

namespace SkylarkSoft\GoRMG\Printembrdroplets\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use SkylarkSoft\GoRMG\Cuttingdroplets\Models\BundleCard;
use SkylarkSoft\GoRMG\Printembrdroplets\Models\PrintReceiveInventory;
use DB;
use Session;

class PrintFactoryProductionRejectionScanController extends Controller
{
    public function rejectionForm()
    {
        $bundle = BundleCard::findOrFail(request('bundeId'));

        return view('printembrdroplets::forms.productions.production_rejection_form', [
            'bundle' => $bundle,
            'type'   => request('type')
        ]);
    }

    public function rejectionFormPost(Request $request)
    {
        $request->validate([
            'bundle_id'     => 'required',
            'rejection_qty' => 'required|numeric|min:1'
        ]);

      /*  try {*/

            $rejectionQty = $request->input('rejection_qty');
            $bundleInfo = DB::table('bundle_cards')
                ->where('id', $request->bundle_id);

            $bundleCard = $bundleInfo->first();

            $bundleTotalRejection = $rejectionQty + $bundleCard->total_rejection;

            if ($bundleTotalRejection < $bundleCard->quantity) {
                $bundleInfo->update([
                    'production_rejection_qty' => $rejectionQty,
                ]);
            }

            $bundleQty = $bundleCard->quantity -
                ($bundleCard->total_rejection + $bundleCard->print_factory_receive_rejection + $request->input('rejection_qty'));
            DB::table('print_factory_reports')->where('bundle_card_id', $request->bundle_id)
                ->update(['production_qty' => $bundleQty]);

            Session::flash('success', S_UPDATE_MSG);

       /* } catch (\Exception $e) {
            Session::flash('error', $e->getMessage());
        }*/

        return redirect('/print-embr-production-scan');
    }
}