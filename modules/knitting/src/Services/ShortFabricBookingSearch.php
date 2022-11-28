<?php

namespace SkylarkSoft\GoRMG\Knitting\Services;

use SkylarkSoft\GoRMG\Merchandising\Models\Bookings\FabricBooking;
use SkylarkSoft\GoRMG\Merchandising\Models\ShortBookings\ShortFabricBooking;
use SkylarkSoft\GoRMG\Knitting\Filters\Filter;

class ShortFabricBookingSearch implements SalesOrderBookingSearchContract
{

    public function format(SalesOrderBookingSearch $salesOrderBookingSearch): array
    {

        $buyerId = $salesOrderBookingSearch->getBuyerId();
        $bookingNo = $salesOrderBookingSearch->getBookingNo();
        $unitId = $salesOrderBookingSearch->getUnitId();
        $bookingStartDate = $salesOrderBookingSearch->getBookingStartDate();
        $bookingEndDate = $salesOrderBookingSearch->getBookingEndDate();
        $isProduction = $salesOrderBookingSearch->getIsProduction();

        return ShortFabricBooking::query()
            ->with(['supplier', 'buyer', 'currency', 'detailsBreakdown.budget.fabricCosting'])
            ->when($buyerId, Filter::applyFilter('buyer_id', $buyerId))
            ->when($bookingNo, Filter::applyFilter('unique_id', $bookingNo))
            ->when($unitId, Filter::applyFilter('supplier_id', $unitId))
            ->when($isProduction, Filter::applyFilter('fabric_source', 1))
            ->when($bookingStartDate && $bookingEndDate, function ($query) use ($bookingStartDate, $bookingEndDate) {
                $query->whereBetween('booking_date', [$bookingStartDate, $bookingEndDate]);
            })->get()->map(function (&$fabricCollection) use ($salesOrderBookingSearch) {
                return $salesOrderBookingSearch->mappedMainShortFabricBreakdown($fabricCollection, $salesOrderBookingSearch);
            })->toArray();
    }
}
