<?php

namespace SkylarkSoft\GoRMG\Inventory\Services\States\TrimsItemState;

use Illuminate\Support\Collection;
use SkylarkSoft\GoRMG\Merchandising\Models\Bookings\TrimsBookingDetails;

interface ItemFormatterContract
{
    public function format(TrimsBookingDetails $detail): Collection;
}
