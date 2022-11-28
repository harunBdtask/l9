<?php


namespace SkylarkSoft\GoRMG\Merchandising\Services\Budget\Costings;

class CostingState
{
    public static function setState($type): CostingContract
    {
        $types = [
            "fabric_costing" => new FabricCosting(),
            "trims_costing" => new TrimsCosting(),
            "embellishment_cost" => new EmbellishmentCosting(),
            "commercial_cost" => new CommercialCosting(),
            "commission_cost" => new CommissionCosting(),
            "wash_cost" => new WashCosting(),
        ];

        return collect($types)->get($type);
    }
}
