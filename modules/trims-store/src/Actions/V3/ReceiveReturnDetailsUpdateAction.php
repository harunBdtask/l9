<?php

namespace SkylarkSoft\GoRMG\TrimsStore\Actions\V3;

use SkylarkSoft\GoRMG\TrimsStore\Models\V3\TrimsStoreReceiveReturn\TrimsStoreReceiveReturn;

class ReceiveReturnDetailsUpdateAction
{
    public function update(TrimsStoreReceiveReturn $receiveReturn)
    {
        $receiveReturn->details()->update([
            'factory_id' => $receiveReturn['factory_id'],
            'transaction_date' => $receiveReturn['return_date'],
        ]);
    }
}
