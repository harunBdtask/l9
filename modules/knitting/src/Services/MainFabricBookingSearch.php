<?php

namespace SkylarkSoft\GoRMG\Knitting\Services;

use SkylarkSoft\GoRMG\Merchandising\Models\Bookings\FabricBooking;
use SkylarkSoft\GoRMG\Knitting\Filters\Filter;

class MainFabricBookingSearch implements SalesOrderBookingSearchContract
{

    public function format(SalesOrderBookingSearch $salesOrderBookingSearch): array
    {
        $unitId = $salesOrderBookingSearch->getUnitId();
        $buyerId = $salesOrderBookingSearch->getBuyerId();
        $factoryId = $salesOrderBookingSearch->getFactoryId();
        $bookingNo = $salesOrderBookingSearch->getBookingNo();
        $bookingStartDate = $salesOrderBookingSearch->getBookingStartDate();
        $bookingEndDate = $salesOrderBookingSearch->getBookingEndDate();
        $isProduction = $salesOrderBookingSearch->getIsProduction();
        $styleName = $salesOrderBookingSearch->getStyleName();
        $orderNo = $salesOrderBookingSearch->getOrderNo();

        return FabricBooking::query()
            ->with([
                'buyer',
                'currency',
                'supplier',
                'detailsBreakdown.budget.fabricCosting',
                'detailsBreakdown.order.teamLeader:id,screen_name',
            ])
            ->when($buyerId, Filter::applyFilter('buyer_id', $buyerId))
            ->when($unitId, Filter::applyFilter('supplier_id', $unitId))
            ->when($isProduction, Filter::applyFilter('fabric_source', 1))
            ->when($bookingNo, Filter::applyFilter('unique_id', $bookingNo))
            ->when($factoryId, Filter::applyFilter('factory_id', $factoryId))
            ->when($styleName, function ($query) use ($styleName) {
                $query->whereHas('detailsBreakdown', Filter::applyFilter('style_name', $styleName));
            })
            ->when($orderNo, function ($query) use ($orderNo) {
                $query->whereHas('detailsBreakdown', Filter::applyFilter('po_no', $orderNo));
            })
            ->when($bookingStartDate && $bookingEndDate, function ($query) use ($bookingStartDate, $bookingEndDate) {
                $query->whereBetween('booking_date', [$bookingStartDate, $bookingEndDate]);
            })->get()->map(function (&$fabricCollection) use ($salesOrderBookingSearch) {
                return $salesOrderBookingSearch->mappedMainShortFabricBreakdown($fabricCollection, $salesOrderBookingSearch);
            })->toArray();
    }

}
