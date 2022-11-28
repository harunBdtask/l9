<?php

namespace SkylarkSoft\GoRMG\Inventory\Services\FabricStore\FabricIssue;

interface FabricIssueContracts
{
    public function handle(FabricIssueStrategy $strategy);

    public function store(FabricIssueStrategy $strategy);
}
