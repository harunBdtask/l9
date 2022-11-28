<?php

namespace SkylarkSoft\GoRMG\Inventory\Services\FabricStore\FabricTransfer;

interface FabricTransferContracts
{
    public function handle(FabricTransferStrategy $strategy);

    public function store(FabricTransferStrategy $strategy);
}
