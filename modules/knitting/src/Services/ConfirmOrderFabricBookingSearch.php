<?php

namespace SkylarkSoft\GoRMG\Knitting\Services;

use SkylarkSoft\GoRMG\Merchandising\Models\Bookings\FabricBooking;
use SkylarkSoft\GoRMG\Merchandising\Models\Samples\SampleBookingConfirmOrder;
use SkylarkSoft\GoRMG\Knitting\Filters\Filter;

class ConfirmOrderFabricBookingSearch implements SalesOrderBookingSearchContract
{

    public function format(SalesOrderBookingSearch $salesOrderBookingSearch): array
    {

        $buyerId = $salesOrderBookingSearch->getBuyerId();
        $bookingNo = $salesOrderBookingSearch->getBookingNo();
        $unitId = $salesOrderBookingSearch->getUnitId();
        $bookingStartDate = $salesOrderBookingSearch->getBookingStartDate();
        $bookingEndDate = $salesOrderBookingSearch->getBookingEndDate();

        return SampleBookingConfirmOrder::query()
            ->with(['supplier', 'buyer', 'currency', 'details'])
            ->when($buyerId, Filter::applyFilter('buyer_id', $buyerId))
            ->when($bookingNo, Filter::applyFilter('unique_id', $bookingNo))
            ->when($unitId, Filter::applyFilter('supplier_id', $unitId))
            ->when($bookingStartDate && $bookingEndDate, function ($query) use ($bookingStartDate, $bookingEndDate) {
                $query->whereBetween('booking_date', [$bookingStartDate, $bookingEndDate]);
            })->get()->map(function (&$fabricCollection) use ($salesOrderBookingSearch) {
                return $salesOrderBookingSearch->mappedBeforeAfterBookingBreakdown($fabricCollection, $salesOrderBookingSearch);
            })->toArray();
    }

}
