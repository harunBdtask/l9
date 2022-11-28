<?php
/**
 * Created by PhpStorm.
 * User: siam
 * Date: 8/17/19
 * Time: 12:51 PM
 */

namespace SkylarkSoft\GoRMG\Merchandising\Handle\FabricReport;

use SkylarkSoft\GoRMG\Merchandising\Handle\FabricReport\Interfaces\FabricReportInterface;
use SkylarkSoft\GoRMG\Merchandising\Models\BudgetFabricBooking;
use SkylarkSoft\GoRMG\Merchandising\Models\BudgetPoDetail;
use SkylarkSoft\GoRMG\Merchandising\Models\PurchaseOrder;

class DateWiseFabricReport implements FabricReportInterface
{
    private $request;

    public function __construct($request)
    {
        $this->request = $request;
    }

    public function generate()
    {
        $from_date = $this->request->from_date;
        $to_date = $this->request->to_date;
        $purchase_orders = $this->getPurchaseOrderByExFactoryDate($from_date, $to_date);
        $get_budget_id = $this->getBudget($purchase_orders);
        return $this->getFabricStatus($get_budget_id);
    }

    private function getPurchaseOrderByExFactoryDate($from_date, $to_date)
    {
        return PurchaseOrder::where('ex_factory_date', '>=', $from_date)
            ->where('ex_factory_date', '<=', $to_date)
            ->pluck('id');
    }

    private function getBudget($purchase_orders)
    {
        return BudgetPoDetail::whereIn('purchase_order_id', $purchase_orders)
            ->groupBy('budget_id')
            ->pluck('budget_id');
    }

    private function getFabricStatus($get_budget_id)
    {
        return BudgetFabricBooking::with('buyer', 'order', 'fabric_type', 'color')
            ->whereIn('budget_id', $get_budget_id)
            ->get()
            ->sortBy(function ($data) {
                return $data->buyer->name;
            });
    }
}
