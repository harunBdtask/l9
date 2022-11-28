<?php


namespace SkylarkSoft\GoRMG\Merchandising\Requests\Budgets;

class EmbellishmentCostRequest extends BudgetCostRequest
{
    public function key(): string
    {
        return 'embl_cost';
    }

    public function rules(): array
    {
        if (! $this->quotation()) {
            return [];
        }

        $emblCost = $this->costingValue();

        return [
            'sumEmbellishmentCosting.consumption_amount_sum' => [function ($attribute, $value, $fail) use ($emblCost) {
                if ($value > $emblCost) {
                    $exceeds = $value - $emblCost;
                    $message = "Exceeds: ($exceeds). Total Embellishment Cost in Price Quotation is $emblCost";
                    $fail($message);
                }
            }],
        ];
    }
}
