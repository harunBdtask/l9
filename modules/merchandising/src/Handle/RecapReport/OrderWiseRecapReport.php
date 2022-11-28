<?php
/**
 * Created by PhpStorm.
 * User: siam
 * Date: 9/11/19
 * Time: 3:35 PM
 */

namespace SkylarkSoft\GoRMG\Merchandising\Handle\RecapReport;

use SkylarkSoft\GoRMG\Merchandising\Models\OrderItemDetail;
use SkylarkSoft\GoRMG\SystemSettings\Models\Buyer;

class OrderWiseRecapReport
{
    private $request;

    public function __construct($request)
    {
        $this->request = $request;
    }

    public function getReportData()
    {
        $buyer_id = $this->request->input('buyer_id') ?? null;
        $month = $this->request->input('month') ?? date('n', time());
        $year = $this->request->input('year') ?? date('Y', time());

        $order_details = OrderItemDetail::with('item', 'order', 'order.buyer', 'factory');

        $order_details->whereHas('order', function ($q) use ($month, $year, $buyer_id) {
            $q->whereMonth('order_shipment_date', $month);
            $q->whereYear('order_shipment_date', $year);
            if ($buyer_id) {
                $q->where('buyer_id', $buyer_id);
            }
            $q->orderBy('buyer_id', 'asc');
        });

        $order_result = $order_details->get()->map(function ($item) {
            $item->buyer_id = $item->order->buyer_id;

            return $item;
        })->sortBy('order.buyer.name')->groupBy('buyer_id');

        $data['orders'] = $order_result;
        $data['buyers'] = Buyer::pluck('name', 'id')->prepend('Select Buyer', '');

        return $data;
    }
}
