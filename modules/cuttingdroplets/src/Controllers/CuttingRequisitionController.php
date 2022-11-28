<?php

namespace SkylarkSoft\GoRMG\Cuttingdroplets\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use SkylarkSoft\Merchandising\Models\Buyer;
use SkylarkSoft\Merchandising\Models\Fabric_composition;
use SkylarkSoft\Merchandising\Models\Order;
use SkylarkSoft\SystemSettings\Models\Color;
use SkylarkSoft\Knittingdroplets\Models\FabricType;
use SkylarkSoft\Cuttingdroplets\Models\CuttingRequisition;
use SkylarkSoft\Cuttingdroplets\Models\CuttingRequisitionDetail;
use SkylarkSoft\Cuttingdroplets\Requests\CuttingRequisitionRequest;
use SkylarkSoft\Textiledroplets\Models\FinishFabStore;
use SkylarkSoft\SystemSettings\Models\Part;
use SkylarkSoft\SystemSettings\Models\User;
use Session, DB;

class CuttingRequisitionController extends Controller
{
    
	public function index()
	{
		$cutting_requisitions = CuttingRequisition::latest('id')->paginate();
	      
	    return view('cuttingdroplets::pages.cutting_requisitions', [
	        'cutting_requisitions' => $cutting_requisitions
	    ]);
	}

	public function create()
    {
        $cutting_requisition_no = userId().time();
        $buyers = Buyer::pluck('name','id')->all();
        $garments_part = Part::pluck('name','id')->prepend('Select a part', '')->all();
        $fabric_types = FabricType::pluck('fabric_type_name','id')->all();

        return view('cuttingdroplets::forms.cutting_requisition', [
        	'cutting_requisition_no' => $cutting_requisition_no,
            'garments_part' => $garments_part,
            'fabric_types' => $fabric_types,
            'cutting_requisition' => null,
            'buyers' => $buyers
        ]);
    }

    public function store(CuttingRequisitionRequest $request)
    {
        try {
        	DB::beginTransaction();
        	$requisitionDetailsInputs = [];
	        $buyerIds = $request->buyer_id;
	        $factoryId = factoryId();
	        $cuttingRequisitionNo = $request->cutting_requisition_no;

	        $cuttingRequisition = CuttingRequisition::create([
	        	'cutting_requisition_no' => $cuttingRequisitionNo
	        ]);

	        foreach ($buyerIds as $key => $buyerId) {
	            $requisitionDetailsInputs[] = [
	                'buyer_id' => $buyerId,
	                'order_id' => $request->order_id[$key],
	                'fabric_type' => $request->fabric_type[$key],
	                'color_id' => $request->color_id[$key],
                    'garments_part_id' => $request->garments_part_id[$key],
                    'batch_no' => $request->batch_no[$key],
	                'requisition_amount' => $request->requisition_amount[$key],
                    'unit_of_measurement_id' => $request->unit_of_measurement_id[$key],
                    'composition_fabric_id' => $request->composition_fabric_id[$key],
	                'balance_amount' => $request->requisition_amount[$key],
	                'remark' => $request->remark[$key],
	                'factory_id' => $factoryId
	            ];
	        }
	        $cuttingRequisition->cuttingRequisitionDetails()->createMany($requisitionDetailsInputs);   
	        DB::commit();
	        
	        Session::flash('success', 'Successfully added');
	        return redirect('cutting-requisitions');
        } catch (Exception $e) {
        	DB::rollback();
        	Session::flash('error', $e->getMessage());
        }

        return redirect()->back();
    }

    public function show($requisition)
    {
        $data['details'] = CuttingRequisitionDetail::with('buyer', 'order', 'fab_type', 'color', 'order', 'fabric_composition')
            ->where('cutting_requisition_id', $requisition)
            ->get();

        $data['requisition'] = CuttingRequisition::find($requisition);
        $data['created_by'] = User::find($data['requisition']->created_by);
        $data['buyers'] = $data['details']->pluck('buyer.name', 'buyer.id');
        $data['orders'] = $data['details']->pluck('order.order_style_no', 'order.id');
        $data['bookings'] = $data['details']->pluck('order.booking_no', 'order.id');

    	return view('cuttingdroplets::pages.cutting_requisition_details', $data);
    }

    public function edit($id)
    {
    	$buyers = Buyer::pluck('name', 'id')->all();
    	$fabric_types = FabricType::pluck('fabric_type_name', 'id')->all();
        $garments_part = Part::pluck('name','id')->prepend('Select a part', '')->all();
    	$cutting_requisition = CuttingRequisition::with('cuttingRequisitionDetails')->findOrFail($id);
    	$compositions = Fabric_composition::pluck('yarn_composition', 'id');

    	return view('cuttingdroplets::forms.cutting_requisition', [        	
            'buyers' => $buyers,
            'compositions' => $compositions,
            'fabric_types' => $fabric_types,
            'garments_part' => $garments_part,
            'cutting_requisition' => $cutting_requisition,
        ]);    	
    }

    public function update($id, CuttingRequisitionRequest $request)
    {
        try {
        	DB::beginTransaction();
        	$requisitionInputs = [];
	        $buyerIds = $request->buyer_id;
	        $factoryId = factoryId();	    

	        $cuttingRequisition = CuttingRequisition::findOrFail($id);
	        // delete existing requisition details
	        CuttingRequisitionDetail::where('cutting_requisition_id', $id)->delete();//forceDelete();

	        // for new entry after delete
	        foreach ($buyerIds as $key => $buyer) {
	            $requisitionInputs[] = [
                    'buyer_id' => $buyer,
                    'order_id' => $request->order_id[$key],
                    'fabric_type' => $request->fabric_type[$key],
                    'color_id' => $request->color_id[$key],
                    'requisition_amount' => $request->requisition_amount[$key],
                    'composition_fabric_id' => $request->composition_fabric_id[$key],
                    'balance_amount' => $request->requisition_amount[$key],
                    'unit_of_measurement_id' => $request->unit_of_measurement_id[$key],
                    'garments_part_id' => $request->garments_part_id[$key],
                    'batch_no' => $request->batch_no[$key],
                    'remark' => $request->remark[$key],
                    'factory_id' => $factoryId
	            ];
	        }
	        $cuttingRequisition->cuttingRequisitionDetails()->createMany($requisitionInputs);	   
	        DB::commit();

	        Session::flash('success', 'Successfully updated');
	        return redirect('cutting-requisitions');
        } catch (Exception $e) {
        	DB::rollback();
        	Session::flash('error', $e->getMessage());
        }

        return redirect()->back();
    }

    public function cuttingRequisitionApproved($cutting_requisition_id)
    {
    	try {
    		$cuttingRequisition = CuttingRequisition::findOrFail($cutting_requisition_id);
    		$cuttingRequisition->update([
    			'approval_status' => 1 // 1 = approved
    		]);
    		Session::flash('success', 'Successfully approved');
    	} catch (Exception $e) {
    		Session::flash('error', $e->getMessage());
    	}    	
    	return redirect()->back();
    }

    public function searchCuttingRequisitions(Request $request)
    {
    	$cutting_requisitions = CuttingRequisition::latest('id');
		if ($request->from_date != null) {
			$cutting_requisitions = $cutting_requisitions->whereDate('created_at', '>=', $request->from_date);
		}
		if ($request->to_date != null) {
			$cutting_requisitions = $cutting_requisitions->whereDate('created_at', '<=', $request->to_date);
		}
		if ($request->cutting_requisition_no != null) {
			$cutting_requisitions = $cutting_requisitions->where('cutting_requisition_no', 'like', '%'.$request->cutting_requisition_no.'%');
		}
    	$cutting_requisitions = $cutting_requisitions->paginate();
	      
	    return view('cuttingdroplets::pages.cutting_requisitions', [
	        'cutting_requisitions' => $cutting_requisitions,
	        'cutting_requisition_no' => $request->cutting_requisition_no ?? null,
	        'from_date' => $request->from_date ?? null,
	        'to_date' => $request->to_date ?? null,
	    ]);
    }

    public function destroy($id)
    {
    	try {
    		$cuttingRequisition = CuttingRequisition::findOrFail($id);  
			if (getRole() == 'super-admin' || getRole() == 'admin') {
				$cuttingRequisition->delete();
				Session::flash('success', 'Successfully deleted');
			} elseif(getRole() == 'user' && $cuttingRequisition->approval_status == 0) { // 0 = submitted	
				$cuttingRequisition->delete();
				Session::flash('success', 'Successfully deleted');
			} else {
				Session::flash('error', 'You can not delete because already approved');
			}
    	} catch (Exception $e) {
    		Session::flash('error', $e->getMessage());
    	}    	
    	return redirect()->back();
    }

    public function getFabricFabricReceivedStore(Request $request)
    {
        if (!\Request::ajax()) {
            return abort(404);
        }

        $result = FinishFabStore::where([
            'order_id' => $request->order_id,
            'garments_part_id' => $request->garments_part_id,
            'composition_fabric_id' => $request->composition_fabric_id,
            'color_id' => $request->color_id,
            'fabric_type' => $request->fabric_type_id
        ])
        ->selectRaw('sum(today_receive_qty - today_delivery_qty) as available_amount, unit_of_measurement_id')
        ->orderBy('available_amount', 'desc')
        ->groupBy('unit_of_measurement_id')
        ->first();

        if ($result) {
            $result = $result->setAttribute('uom', BOOKING_UNIT_CONSUMPTION[$result->unit_of_measurement_id]);
        }

        return $result;
    }
}
