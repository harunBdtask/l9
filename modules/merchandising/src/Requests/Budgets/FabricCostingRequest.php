<?php


namespace SkylarkSoft\GoRMG\Merchandising\Requests\Budgets;

class FabricCostingRequest extends BudgetCostRequest
{
    public function key(): string
    {
        return 'fab_cost';
    }

    public function rules(): array
    {
        if (! $this->quotation()) {
            return [];
        }

        $fabricCost = $this->costingValue();
        
        return [
            'total_fabric_cost' => [function ($attribute, $value, $fail) use ($fabricCost) {
                if ($value > $fabricCost) {
                    $exceeds = $value - $fabricCost;
                    $message = "Exceeds: ($exceeds). Total Fabric Cost in Price Quotation is $fabricCost";
                    $fail($message);
                }
            }],
        ];
    }
}
