<?php


namespace SkylarkSoft\GoRMG\Merchandising\Requests\Budgets;

class WashCostRequest extends BudgetCostRequest
{
    public function key(): string
    {
        return 'gmt_wash';
    }

    public function rules(): array
    {
        if (! $this->quotation()) {
            return [];
        }

        $cost = $this->costingValue();

        return [
            'sumWashCosting.consumption_amount_sum' => [function ($attribute, $value, $fail) use ($cost) {
                if ($value > $cost) {
                    $exceeds = $value - $cost;
                    $message = "Exceeds: ($exceeds). Total Wash Cost in Price Quotation is $cost";
                    $fail($message);
                }
            }],
        ];
    }
}
