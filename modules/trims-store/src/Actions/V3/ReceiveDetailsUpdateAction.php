<?php

namespace SkylarkSoft\GoRMG\TrimsStore\Actions\V3;

use SkylarkSoft\GoRMG\TrimsStore\Models\V3\TrimsStoreReceive\TrimsStoreReceive;

class ReceiveDetailsUpdateAction
{
    public function update(TrimsStoreReceive $receive)
    {
        $receive->details()->update([
            'factory_id' => $receive['factory_id'],
            'transaction_date' => $receive['receive_date'],
        ]);
    }
}
