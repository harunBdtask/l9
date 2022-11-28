<?php

namespace SkylarkSoft\GoRMG\Inventory\Actions\TrimsStoreActions\TrimsStoreInventory;

use SkylarkSoft\GoRMG\Inventory\Models\TrimsStore\TrimsInventory\TrimsInventory;

class SyncTrimsInventoryAction
{
    public function handle(TrimsInventory $inventory)
    {
        $inventory->load('details');

        $inventory->update([
            'delivery_qty' => $inventory->getRelation('details')->sum('receive_qty'),
        ]);
    }
}
