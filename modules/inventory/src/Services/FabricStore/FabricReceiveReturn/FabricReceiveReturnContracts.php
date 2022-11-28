<?php

namespace SkylarkSoft\GoRMG\Inventory\Services\FabricStore\FabricReceiveReturn;

interface FabricReceiveReturnContracts
{
    public function handle(FabricReceiveReturnStrategy $strategy);

    public function store(FabricReceiveReturnStrategy $strategy);
}
