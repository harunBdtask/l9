<?php

namespace SkylarkSoft\GoRMG\TimeAndAction\Services\IntegrateWithPage;

use Carbon\Carbon;
use SkylarkSoft\GoRMG\Merchandising\Models\PriceQuotation;

class PriceQuotationIntegrate implements IntegrateWithPageContract
{

    public function actualDate(PageState $state): array
    {
        $order = $state->getOrder();

        $priceQuotation = PriceQuotation::query()
            ->where('id', $order->order_copy_from)
            ->first();

        return [
            'start_date' => Carbon::parse($priceQuotation->confirm_date),
            'finish_date' => Carbon::parse($priceQuotation->confirm_date)
        ];
    }
}
