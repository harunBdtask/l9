<?php

namespace SkylarkSoft\GoRMG\Merchandising\Services\ChartService\Consumers;

use SkylarkSoft\GoRMG\Merchandising\Models\Order;
use SkylarkSoft\GoRMG\Merchandising\Models\PurchaseOrder;
use SkylarkSoft\GoRMG\Merchandising\Services\ChartService\Concerns\ChartServiceContract;

class OrderChartService extends ChartServiceContract
{

    private $values = [];

    public function getValues(): array
    {
        if (!$this->values) {
            $totalOrders = Order::all()->count();

//            $purchaseOrder = PurchaseOrder::query()->get();

            $poApproved = Order::query()
                ->withCount('purchaseOrders')
                ->withCount('approvedPurchaseOrders')
                ->get()
                ->filter(function ($collection) {
                    return $collection->purchase_orders_count === $collection->approved_purchase_orders_count;
                })->count();

            $unApprovedPo = $totalOrders - $poApproved;

            $this->values = [
                $totalOrders,
                $poApproved,
                $unApprovedPo,
            ];
        }

        return $this->values;
    }

    public function renderIn(): string
    {
        return 'merchandising::chart-service.chart';
    }

    public function getLevels(): array
    {
        return [
            'Total Orders',
            'Approved Order',
            'Un Approved Order',
        ];
    }

    public function getType(): string
    {
        return self::BAR;
    }


}
