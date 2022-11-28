<?php

namespace SkylarkSoft\GoRMG\Finishingdroplets\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use SkylarkSoft\GoRMG\Cuttingdroplets\Models\TotalProductionReport;
use SkylarkSoft\GoRMG\Finishingdroplets\Requests\PolyCartoonRequest;
use SkylarkSoft\GoRMG\SystemSettings\Models\Buyer;
use SkylarkSoft\GoRMG\SystemSettings\Models\Floor;
use SkylarkSoft\GoRMG\Finishingdroplets\Models\PolyCartoon;
use Session, DB;
use SkylarkSoft\GoRMG\Merchandising\Models\Order;

class PolyCartoonController extends Controller
{    
    public function index()
    {
        $poly_cartoons = PolyCartoon::with([
            'buyer:id,name', 
            'purchaseOrder:id,po_no', 
            'color:id,name', 
            'size:id,name', 
            'floor:id,floor_no'
        ])->orderBy('id', 'desc')->paginate();

    	return view('finishingdroplets::pages.view_poly_cartoon')
            ->with('poly_cartoons', $poly_cartoons);
    }

    public function create()
    {
        $buyers = Buyer::pluck('name', 'id')->all();
        $floors = Floor::pluck('floor_no', 'id')->all(); 

    	return view('finishingdroplets::forms.add_poly_cartoon', [
    		'buyers' => $buyers,
    		'floors' => $floors
    	]);
    }

    public function store(PolyCartoonRequest $request)
    {
        try {
            DB::beginTransaction();
            PolyCartoon::create($request->all());

            DB::commit();
            Session::flash('success', S_SAVE_MSG);
        } catch(\Exception $e){
            DB::rollBack();
            Session::flash('error', $e->getMessage());
        }        
        return redirect()->back();
    }

    public function deletePolyCartoon($id)
    {
        try {
            DB::beginTransaction();
            PolyCartoon::destroy($id);

            DB::commit();
            $status = SUCCESS;
        } catch(\Exception $e){
            DB::rollBack();
            $status = FAIL;
        }
        return $status;
    }

    // For TNA
    public static function getOrderWiseActualPolyDateInfo($order_id)
    {
        $polyQuery = PolyCartoon::where(['order_id' => $order_id]);
        $actual_start = '';
        $actual_end = '';
        $duration = '';
        if($polyQuery->count()) {
            $order_qty = Order::findOrFail($order_id)->total_quantity;
            $firstPoly = $polyQuery->orderBy('created_at', 'asc')->first();
            $actual_start = date('Y-m-d', strtotime($firstPoly->created_at));
            $polyQueryClone = clone $polyQuery;
            $totalPolyQty = $polyQueryClone->sum('poly_qty') - $polyQueryClone->sum('short_reject_qty');
            $lastPoly = $polyQueryClone->orderBy('created_at', 'desc')->first();
            if ($totalPolyQty >= $order_qty) {
                $actual_end = date('Y-m-d', strtotime($lastPoly->created_at));
                $duration = calculateDays($actual_start, $actual_end);
            }
        }

        return [
            'actual_start' => $actual_start,
            'actual_end' => $actual_end,
            'actual_duration' => $duration,
        ];
    }


}
