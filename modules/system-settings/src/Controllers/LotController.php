<?php

namespace SkylarkSoft\GoRMG\SystemSettings\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use SkylarkSoft\GoRMG\SystemSettings\Models\Buyer;
use SkylarkSoft\GoRMG\SystemSettings\Models\Color;
use SkylarkSoft\GoRMG\SystemSettings\Models\Lot;
use SkylarkSoft\GoRMG\SystemSettings\Requests\LotRequest;

class LotController extends Controller
{
    public function index()
    {
        $lots = Lot::withoutGlobalScope('factoryId')
            ->orderBy('id', 'DESC')
            ->where('lots.factory_id', factoryId())
            ->paginate();

        return view('system-settings::pages.lots', ['lots' => $lots]);
    }

    public function create()
    {
        $buyers = Buyer::withoutGlobalScope('factoryId')->pluck('name', 'id');

        return view('system-settings::forms.lot', ['lot' => null, 'buyers' => $buyers]);
    }

    public function store(LotRequest $request)
    {
        try {
            DB::beginTransaction();
            $lot = Lot::create($request->all());
            $purchaseOrderIds = $request->get('purchase_order_id');
            $lot->purchaseOrders()->attach($purchaseOrderIds);
            DB::commit();
            Session::flash('success', S_SAVE_MSG);
        } catch (Exception $e) {
            Session::flash('success', $e->getMessage());
        }

        return redirect('/lots');
    }

    public function edit($id)
    {
        $lot = Lot::with(['purchaseOrders', 'color', 'order'])->findOrFail($id);
        $buyers = Buyer::withoutGlobalScope('factoryId')->pluck('name', 'id')->all();
        $buyer = $lot->purchaseOrders->first()->buyer;
        $purchaseOrders = $buyer->purchaseOrders->pluck('po_no', 'id')->all();
        $colors = Color::pluck('name', 'id')->all();
        $lot->buyer_id = $buyer->id;
        $lot->purchase_order_id = $lot->purchaseOrders->pluck('id')->all();

        return view('system-settings::forms.lot', [
            'lot' => $lot,
            'colors' => $colors,
            'purchaseOrders' => $purchaseOrders,
            'buyers' => $buyers,
        ]);
    }

    public function update($id, LotRequest $request)
    {
        try {
            DB::beginTransaction();
            $lot = Lot::findOrFail($id);
            $lot->update($request->all());
            $purchaseOrderIds = $request->get('purchase_order_id');
            $lot->purchaseOrders()->sync($purchaseOrderIds);
            DB::commit();
            Session::flash('success', S_UPDATE_MSG);
        } catch (Exception $e) {
            Session::flash('success', $e->getMessage());
        }

        return redirect('/lots');
    }

    public function destroy($id)
    {
        $lot = Lot::findOrFail($id);
        if ($lot->bundleCards->count()) {
            Session::flash('error', 'Cannot delete because BundleCard already generated for this lot!');

            return redirect('lots');
        }

        $lot = Lot::findOrFail($id);
        $lot->delete();

        Session::flash('success', 'Lot deleted successfully!');

        return redirect('/lots');
    }

    public function getLots($order_id)
    {
        return Lot::getLots($order_id);
    }

    public function searchLots(Request $request)
    {
        $q = $request->q;
        if ($q == '') {
            return redirect('lots');
        }

        $lots = Lot::withoutGlobalScope('factoryId')
            ->join('lot_order', 'lots.id', 'lot_order.lot_id')
            ->join('purchase_orders', 'lot_order.purchase_order_id', 'purchase_orders.id')
            ->join('orders', 'purchase_orders.order_id', 'orders.id')
            ->join('buyers', 'orders.buyer_id', 'buyers.id')
            ->join('colors', 'lots.color_id', 'colors.id')
            ->where('lots.factory_id', factoryId())
            ->where(function ($q) use ($request) {
                $q->where('lots.lot_no', 'like', '%' . $request->q . '%')
                ->orWhere('lots.fabric_received_at', 'like', '%' . $request->q . '%')
                ->orWhere('purchase_orders.po_no', 'like', '%' . $request->q . '%')
                ->orWhere('buyers.name', 'like', '%' . $request->q . '%')
                ->orWhere('orders.style_name', 'like', '%' . $request->q . '%')
                ->orWhere('colors.name', 'like', '%' . $request->q . '%');
            })
            ->select('lots.*', 'orders.style_name','orders.job_no','orders.repeat_no', 'buyers.name as buyer_name')
            ->orderBy('lots.id', 'DESC')
            ->paginate();

        return view('system-settings::pages.lots', ['lots' => $lots, 'q' => $request->q]);
    }
}
