<?php


namespace SkylarkSoft\GoRMG\Merchandising\Requests\Budgets;

class CommissionCostRequest extends BudgetCostRequest
{
    public function key(): string
    {
        return 'commi_dzn';
    }

    public function rules(): array
    {
        if (! $this->quotation()) {
            return [];
        }

        $cost = $this->costingValue();

        return [
            'sumCommissionCosting.amount_sum' => [function ($attribute, $value, $fail) use ($cost) {
                if ($value > $cost) {
                    $exceeds = $value - $cost;
                    $message = "Exceeds: ($exceeds). Total Commission Cost in Price Quotation is $cost";
                    $fail($message);
                }
            }],
        ];
    }
}
