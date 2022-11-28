<?php
/**
 * Created by PhpStorm.
 * User: siam
 * Date: 8/18/19
 * Time: 1:18 PM
 */

namespace SkylarkSoft\GoRMG\Merchandising\Handle\FabricReport;

use SkylarkSoft\GoRMG\Merchandising\Handle\FabricReport\Interfaces\FabricReportInterface;
use SkylarkSoft\GoRMG\Merchandising\Models\BudgetFabricBooking;
use SkylarkSoft\GoRMG\Merchandising\Models\BudgetPoDetail;
use SkylarkSoft\GoRMG\Merchandising\Models\PurchaseOrder;

class MonthlyFabricReport implements FabricReportInterface
{
    private $request;

    public function __construct($request)
    {
        $this->request = $request;
    }

    public function generate()
    {
        $month = $this->request->month;
        $purchase_orders = $this->getPurchaseOrderByExFactoryDate($month);
        $get_budget_id = $this->getBudget($purchase_orders);
        return $this->getFabricStatus($get_budget_id);
    }

    private function getPurchaseOrderByExFactoryDate($month)
    {
        return PurchaseOrder::whereMonth('ex_factory_date', date('m', strtotime($month)))
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
