<?php

namespace SkylarkSoft\GoRMG\Finishingdroplets\Controllers;

use App\Http\Controllers\Controller;
use SkylarkSoft\GoRMG\Iedroplets\Models\Shipment;
use Dompdf\Exception;
use Session;

class ShipmentController extends Controller
{
   
    public function updateShipmentStatus()
    {
    	$shipment_approval_list = Shipment::where('status',0)
            ->where('ship_quantity', '>', 0)
            ->orderBy('id','desc')
            ->paginate();

    	return view('finishingdroplets::pages.shipment_approval_list',[
    	    'shipment_approval_list' => $shipment_approval_list
        ]);
    }

    public function shipmentStatusApproval($order_id)
    {
        try {            
            $shipment = Shipment::where('order_id',$order_id)
                ->where('ship_quantity', '>', 0)
                ->where('status',0)
                ->update(['status' => 1]);
            Session::flash('alert-success', 'This Shipment is approved!');
            return redirect('/shipment-status-update');

        } catch (Exception $e) {
            Session::flash('alert-danger', 'Something went wrong! Please Try again!');
            return redirect('/shipment-status-update');
        }
    }
}
