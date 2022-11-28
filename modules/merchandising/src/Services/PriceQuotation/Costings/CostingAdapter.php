<?php

namespace SkylarkSoft\GoRMG\Merchandising\Services\PriceQuotation\Costings;

class CostingAdapter
{
    private $adapter;
    private $budgetId;

    public function __construct($type)
    {
        $types = [
            "fabric_costing" => new FabricCostingFormatter(),
            "trims_costing" => new TrimsCostingFormatter(),
            "embellishment_cost" => new EmbellishmentCostingFormatter(),
            "commercial_cost" => new CommercialCostingFormatter(),
            "commission_cost" => new CommissionCostingFormatter(),
            "wash_cost" => new WashCostingFormatter(),
        ];
        $this->adapter = collect($types)->get($type);
    }

    public static function setState($type): CostingAdapter
    {
        return new static($type);
    }

    public function setBudgetId($budgetId): CostingAdapter
    {
        $this->budgetId = $budgetId;
        return $this;
    }

    public function getBudgetId()
    {
        return $this->budgetId;
    }

    public function doFormat($details)
    {
        return $this->adapter->format($this->getBudgetId(), $details);
    }
}
