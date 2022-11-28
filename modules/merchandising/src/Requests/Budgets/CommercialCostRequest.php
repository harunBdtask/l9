<?php


namespace SkylarkSoft\GoRMG\Merchandising\Requests\Budgets;

class CommercialCostRequest extends BudgetCostRequest
{
    public function key(): string
    {
        return 'comml_cost';
    }

    public function rules(): array
    {
        if (! $this->quotation()) {
            return [];
        }

        $cost = $this->costingValue();

        return [
            'sumCommercialCosting.amount_sum' => [function ($attribute, $value, $fail) use ($cost) {
                if ($value > $cost) {
                    $exceeds = $value - $cost;
                    $message = "Exceeds: ($exceeds). Total Commercial Cost in Price Quotation is $cost";
                    $fail($message);
                }
            }],
        ];
    }
}
