<?php

namespace SkylarkSoft\GoRMG\Merchandising\Services;

use SkylarkSoft\GoRMG\Merchandising\Models\Bookings\TrimsBookingDetails;

class PriceComparisonReportService
{
    public $item_name, $from_date, $to_date;

    public function __construct($request)
    {
        $this->item_name = $request->get('item_name');
        $this->from_date = $request->get('from_date');
        $this->to_date = $request->get('to_date');
    }

    /**
     * @return array
     */
    public function report(): array
    {
        $prevPrice = 0;
        $lowestPrice = 0;
        $data = TrimsBookingDetails::query()
            ->where('item_name', $this->item_name)
            ->whereHas('booking', function ($query) {
                $query->whereDate('booking_date', '>=', $this->from_date)
                    ->whereDate('booking_date', '<=', $this->to_date);
            })
            ->with('booking.buyer:id,name', 'booking.supplier:id,name')
            ->orderBy('work_order_rate')
            ->get()
            ->map(function ($item, $key) use (&$prevPrice, &$lowestPrice) {
                if ($key == 0) {
                    $lowestPrice = $item->work_order_rate;;
                }
                $priceIncrease = $prevPrice ? ((((double)$item->work_order_rate - $prevPrice) / $prevPrice) * 100) : 0;
                $prevPrice = $item->work_order_rate;
                return [
                    'buyer_name' => $item->booking->buyer->name ?? '',
                    'style_name' => $item->style_name,
                    'supplier_name' => $item->booking->supplier->name ?? '',
                    'work_order_no' => $item->budget_unique_id,
                    'total_order_qty' => $item->total_qty,
                    'unit_price' => $item->work_order_rate,
                    'price_increased' => (int)$priceIncrease,
                    'we_can_save' => ((double)$item->work_order_rate - $lowestPrice) * (double)$item->total_qty,
                ];
            });

        $reportData['data'] = $data;
        $reportData['itemName'] = $this->item_name;
        $reportData['highestPrice'] = $data->last()['unit_price'] ?? 0.00;
        $reportData['lowestPrice'] = $data->first()['unit_price'] ?? 0.00;
        $reportData['totalSave'] = $data->sum('we_can_save');

        return $reportData;
    }
}
