<?php

namespace SkylarkSoft\GoRMG\Merchandising\Services\YarnPurchaseOrder;

use Illuminate\Support\Collection;

interface DetailsFormatter
{
    public static function format($collections): Collection;
}
