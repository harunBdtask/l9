<?php

namespace SkylarkSoft\GoRMG\TimeAndAction\Services;

use SkylarkSoft\GoRMG\TimeAndAction\Models\TNAReports;

class TNAPreviousDataDelete
{
    public function deleteForStyle($data)
    {
        TNAReports::query()
            ->where('factory_id', $data->factory_id)
            ->where('buyer_id', $data->buyer_id)
            ->where('order_id', $data->id)
            ->where('based_on', TNAReports::ORDER_WISE)
            ->delete();
    }

    public function deleteForPO($data)
    {
        TNAReports::query()
            ->where('factory_id', $data->factory_id)
            ->where('buyer_id', $data->buyer_id)
            ->where('order_id', $data->order_id)
            ->where('po_id', $data->id)
            ->where('based_on', TNAReports::PO_WISE)
            ->delete();
    }
}
