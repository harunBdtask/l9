<?php

namespace SkylarkSoft\GoRMG\Merchandising\Transform\PriceQuotation;

use SkylarkSoft\GoRMG\Merchandising\Models\PriceQuotation;

interface TransformInterface
{
    public function transform(PriceQuotation $priceQuotation): array;
}
