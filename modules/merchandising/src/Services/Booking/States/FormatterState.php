<?php

namespace SkylarkSoft\GoRMG\Merchandising\Services\Booking\States;

use SkylarkSoft\GoRMG\Merchandising\Models\Bookings\TrimsBookingItemDetails;
use SkylarkSoft\GoRMG\Merchandising\Models\Bookings\TrimsVirtualStock;
use SkylarkSoft\GoRMG\Merchandising\Models\ShortBookings\ShortTrimsBookingItemDetails;
use SkylarkSoft\GoRMG\Merchandising\Models\WorkOrders\EmbellishmentBookingItemDetails;

class FormatterState
{
    private $implement;
    private $stockImplement;
    private $filter = [];
    private $implementors = [
        'trims_booking' => TrimsBookingItemDetails::class,
        'short_trims_booking' => ShortTrimsBookingItemDetails::class,
        'embellishment_booking' => EmbellishmentBookingItemDetails::class,
    ];


    private $stockLookup = [
        'trims_booking' => TrimsVirtualStock::class,
        'short_trims_booking' => null,
        'embellishment_booking' => null,
    ];

    public function setState(string $model): FormatterState
    {
        $this->implement = new $this->implementors[$model];
        $this->stockImplement = isset($this->stockLookup[$model]) ? new $this->stockLookup[$model] : null;

        return $this;
    }

    public function filters(array $criteria): FormatterState
    {
        $this->filter = $criteria;

        return $this;
    }

    public function bookedQty($field)
    {
        return $this->implement->where($this->filter)->sum($field);
    }

    public function stockQty($field)
    {
        if (!$this->stockImplement) {
            return null;
        }
        return $this->stockImplement->where($this->filter)->sum($field);
    }
}
