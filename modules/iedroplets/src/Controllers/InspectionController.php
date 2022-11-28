<?php

namespace SkylarkSoft\GoRMG\Iedroplets\Controllers;

use App\Http\Controllers\Controller;
use Exception;
use Illuminate\Http\Request;
use SkylarkSoft\GoRMG\Iedroplets\Models\InspectionSchedule;
use SkylarkSoft\GoRMG\Iedroplets\PackageConst;
use SkylarkSoft\GoRMG\Merchandising\Models\Order;
use SkylarkSoft\GoRMG\Merchandising\Models\PurchaseOrder;
use SkylarkSoft\GoRMG\SystemSettings\Models\Buyer;

class InspectionController extends Controller
{

    public function getInspectionDateAndUnitPriceUpdate()
    {
        $buyers = Buyer::pluck('name', 'id')->all();

        return view(PackageConst::PACKAGE_NAME . '::forms.inspection_date_and_unit_price_update')
            ->with('buyers', $buyers);
    }

    public function getPurchaseOrders($orderId)
    {
        return PurchaseOrder::where('order_id', $orderId)->get();
    }

    public function getInspectionDateAndUnitPriceUpdatePost(Request $request): int
    {
        $ids = $request->get('ids');
        $ex_factory_dates = $request->get('ex_factory_date');
        $unit_prices = $request->get('unit_price');
        $count = 0;

        foreach ($ids as $key => $po_id) {
            if ($ex_factory_dates[$key]) {
                $purchase_order = PurchaseOrder::find($po_id);
                $purchase_order->update([
                    'ex_factory_date' => $ex_factory_dates[$key],
                    'unit_price' => $unit_prices[$key]
                ]);
                ++$count;
            }
        }

        return $count > 0 ? SUCCESS : FAIL;
    }

    public function inspectionDateAndQuantityUpdate()
    {
        $buyers = Buyer::pluck('name', 'id')->all();

        return view(PackageConst::PACKAGE_NAME . '::forms.inspection_date_update')
            ->with('buyers', $buyers);
    }

    public function getOrdersForInspectionUpdate($order_id): array
    {
        $result = [];
        $keyForNew = 0;

        $orderStyle = Order::with('inspectionSchedule')
            ->findOrFail($order_id);

        if ($orderStyle->inspectionSchedule) {

            foreach ($orderStyle->inspectionSchedule as $key => $inspection) {

                $result[$key]['id'] = $inspection->id;
                $result[$key]['order_id'] = $inspection->order_id;
                $result[$key]['buyer_reference'] = $orderStyle->buyer_reference ?? '';
                $result[$key]['inspection_date'] = $inspection->inspection_date;
                $result[$key]['inspection_quantity'] = $inspection->inspection_quantity;
                $result[$key]['remarks'] = $inspection->remarks ?? '';
                $result[$key]['status'] = $inspection->status ?? '';

            }

            $keyForNew = $orderStyle->inspectionSchedule->count();
        }

        $result[$keyForNew]['id'] = null;
        $result[$keyForNew]['order_id'] = $orderStyle->id;
        $result[$keyForNew]['buyer_reference'] = $orderStyle->buyer_reference ?? '';
        $result[$keyForNew]['inspection_date'] = null;
        $result[$keyForNew]['inspection_quantity'] = '';
        $result[$keyForNew]['remarks'] = '';
        $result[$keyForNew]['status'] = 0;

        return $result;
    }

    public function inspectionScheduleDateAndQuantityUpdatePost(Request $request): int
    {
        try {
            $inspectionSchedule = InspectionSchedule::firstOrNew(['id' => $request->id]);
            $inspectionSchedule->order_id = $request->order_id;
            $inspectionSchedule->inspection_date = $request->inspection_date;
            $inspectionSchedule->inspection_quantity = $request->inspection_quantity;
            $inspectionSchedule->remarks = $request->remarks;
            $inspectionSchedule->save();

            $status = 200;
        } catch (Exception $e) {
            $status = 500;
        } finally {
            return $status;
        }
    }

    public function inspectionScheduleStatusUpdatePost(Request $request): int
    {
        try {
            $inspectionSchedule = InspectionSchedule::findorFail($request->id);
            $inspectionSchedule->status = $request->status;
            $inspectionSchedule->save();

            $status = 200;
        } catch (Exception $e) {
            $status = 500;
        } finally {
            return $status;
        }
    }

}
