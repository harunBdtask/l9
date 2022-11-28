<?php

namespace SkylarkSoft\GoRMG\Inventory\Services\FabricStore\FabricIssueReturn;

interface FabricIssueReturnContracts
{
    public function handle(FabricIssueReturnStrategy $strategy);

    public function store(FabricIssueReturnStrategy $strategy);
}
