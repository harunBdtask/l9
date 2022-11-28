<?php

namespace SkylarkSoft\GoRMG\TimeAndAction\Services\IntegrateWithPage;

use Carbon\Carbon;

class OrderIntegrate implements IntegrateWithPageContract
{

    public function actualDate(PageState $state): array
    {
        $order = $state->getOrder();
        return [
            'start_date' => Carbon::parse($order->created_at),
            'finish_date' => Carbon::parse($order->created_at)
        ];
    }
}
