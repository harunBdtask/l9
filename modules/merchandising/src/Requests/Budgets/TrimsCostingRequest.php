<?php


namespace SkylarkSoft\GoRMG\Merchandising\Requests\Budgets;

class TrimsCostingRequest extends BudgetCostRequest
{
    public function key(): string
    {
        return 'trims_cost';
    }

    public function rules(): array
    {
        if (! $this->quotation()) {
            return [];
        }

        $trims_cost = $this->costingValue();

        return [
            'calculation.amount_sum' => [function ($attribute, $value, $fail) use ($trims_cost) {
                if (sprintf('%0.2f', $value) > sprintf('%0.2f', $trims_cost) ) {
                    $exceeds = $value - $trims_cost;
                    $message = "Exceeds: ($exceeds). Total Trims Cost in Price Quotation is $trims_cost";
                    $fail($message);
                }
            }],
        ];
    }
}
