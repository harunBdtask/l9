<?php

namespace SkylarkSoft\GoRMG\Knitting\Services;

use SkylarkSoft\GoRMG\Knitting\Filters\Filter;
use SkylarkSoft\GoRMG\Sample\Models\SampleOrderRequisition;
use SkylarkSoft\GoRMG\Sample\Services\SampleRequisition\SampleOrderRequisitionService;
use SkylarkSoft\GoRMG\SystemSettings\Services\DiaTypesService;

class SampleSearch implements SalesOrderBookingSearchContract
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

        return SampleOrderRequisition::query()
            ->with([
                'buyer',
            ])
            ->when($buyerId, Filter::applyFilter('buyer_id', $buyerId))
            ->when($factoryId, Filter::applyFilter('factory_id', $factoryId))
            ->get()->map(function (&$fabricCollection) use ($salesOrderBookingSearch) {
                $fabricCollection->dia_types = DiaTypesService::diaTypes();
                $fabricCollection->fabricUoms = SampleOrderRequisitionService::fabricUoms();
                return $salesOrderBookingSearch->mappedSampleBreakdown($fabricCollection, $salesOrderBookingSearch);
            })->toArray();
    }

}
