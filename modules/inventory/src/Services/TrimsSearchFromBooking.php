<?php


namespace SkylarkSoft\GoRMG\Inventory\Services;

use SkylarkSoft\GoRMG\Merchandising\Models\Bookings\TrimsBooking;
use SkylarkSoft\GoRMG\Merchandising\Models\PurchaseOrder;
use SkylarkSoft\GoRMG\Merchandising\Models\ShortBookings\ShortTrimsBooking;

class TrimsSearchFromBooking implements TrimsInfoSearchInterface
{
    const MAIN = 'main';
    const SHORT = 'short';

    public $data = [];

    public function search(): array
    {
        $bookings = $this->getTrimsData();

        foreach ($bookings as $booking) {

            if ( !isset($booking->details) || !count($booking->details) ) {
                continue;
            }

            $data['unique_id'] = $booking->unique_id;
            $data['buyer_id'] = $booking->buyer_id;
            $data['buyer'] = $booking->buyer->name;
            $data['supplier_id'] = $booking->supplier_id;
            $data['supplier'] = $booking->supplier->name;
            $data['year'] = $booking->bookingDateYear();
            $data['booking_date'] = $booking->booking_date;
            $data['delivery_date'] = $booking->delivery_date;
            $data['source'] = $booking->source;
            $data['currency'] = $booking->currency;
            $data['type'] = $booking::TYPE;
            $data['booking_no'] = $booking->unique_id;

            foreach ($booking->details as $key => $detail) {
                if ( $this->doesNotHaveDetails($booking) ) {
                    continue;
                }

                if ( !$key ) {
                    $poNo = collect($detail->details)->pluck('po_no')->flatten()->unique();
                    $data['order_no'] = $poNo->implode(',');
                    $poQuantity = PurchaseOrder::whereIn('po_no', $poNo->all())->sum('po_quantity');
                    $data['order_qty'] = $poQuantity;
                }


                foreach ($detail->details as $item) {

                    $poNo = is_array($item['po_no']) ? collect($item['po_no'])->values() : [$item['po_no']];

                    if ( !isset($data['shipment_date']) || !$data['shipment_date'] ) {
                        $purchaseOrder = PurchaseOrder::where('po_no', $poNo[0])->first();
                        $data['shipment_date'] = $purchaseOrder ? $purchaseOrder->ex_factory_date : null;
                    }

                    $data['details'][] = $this->formatToArray($detail, $booking, $poNo, $item);
                }

            }


            $this->data[] = $data;
        }

        return $this->data;
    }

    private function getTrimsData()
    {
        $supplierId = request('supplier_id');
        $buyerId = request('buyer_id',);
        $workOrderNo = request('wo_no');
        $fromDate = request('form');
        $toDate = request('to');
        $trimsType = request('type');

        $query = TrimsBooking::query();

        if ( $trimsType === self::SHORT ) {
            $query = ShortTrimsBooking::query();
        }

        return $query->with('details', 'buyer', 'supplier')
            ->when($supplierId, function ($query) use ($supplierId) {
                return $query->where('supplier_id', $supplierId);
            })
            ->when($buyerId, function ($query) use ($buyerId) {
                return $query->where('buyer_id', $buyerId);
            })
            ->when($workOrderNo, function ($query) use ($workOrderNo) {
                return $query->where('unique_id', 'LIKE', '%' . $workOrderNo . '%');
            })
            ->when($fromDate && $toDate, function ($q) {
                return $q;
            })
            ->whereHas('details', function ($q) {
                return $q->whereNotNull('details');
            })
            ->get();
    }

    private function doesNotHaveDetails($booking): bool
    {
        return !isset($booking->details) || !count($booking->details);
    }

    /**
     * @param $detail
     * @param $booking
     * @param $poNo
     * @param $item
     * @return array
     */
    public function formatToArray($detail, $booking, $poNo, $item): array
    {
        return [
            'order_uniq_id'                => $detail->budget_unique_id, /*Actually It's Order Entry Uniq ID*/
            'sensitivity'                  => $detail->sensitivity,
            'ship_date'                    => $booking->delivery_date,
            'style_name'                   => $detail->style_name,
            'po_no'                        => $poNo,
            'ref_no'                       => null,
            'brand_sup_ref'                => null,
            'item_id'                      => $detail->item_id,
            'item_name'                    => $detail->item_name,
            'item_description'             => $detail->item_description,
            'gmts_sizes'                   => $item['size'],
            'item_color'                   => $item['color'],
            'item_size'                    => $item['item_size'],
            'uom_id'                       => $detail->cons_uom_id,
            'uom_name'                     => $detail->cons_uom_value,
            'wo_pi_qty'                    => $item['wo_total_qty'],
            'receive_qty'                  => null,
            'rate'                         => $item['rate'],
            'amount'                       => number_format($item['pcs'] * $item['rate'], 4),
            'reject_qty'                   => null,
            'payment_for_over_receive_qty' => null,
            'floor'                        => null,
            'room'                         => null,
            'rack'                         => null,
            'shelf'                        => null,
            'bin'                          => null,
        ];
    }
}
