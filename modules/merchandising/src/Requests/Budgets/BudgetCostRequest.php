<?php


namespace SkylarkSoft\GoRMG\Merchandising\Requests\Budgets;

use Illuminate\Foundation\Http\FormRequest;
use SkylarkSoft\GoRMG\Merchandising\Models\Budget;
use SkylarkSoft\GoRMG\SystemSettings\Models\MerchandisingVariableSettings;

abstract class BudgetCostRequest extends FormRequest
{
    abstract public function key(): string;

    public function authorize(): bool
    {
        return true;
    }

    public function budget()
    {
        return Budget::with('quotation')->find($this->budget_id);
    }

    public function quotation()
    {
        if (! $this->variableCheck()) {
            return false;
        }

        return $this->budget()->quotation;
    }

    public function costingValue()
    {
        return $this->quotation()->{$this->key()};
    }

    public function variableCheck(): bool
    {
        $variable = $this->variableSettings();

        if (isset($variable->variables_details['budget_validate_with_price_quotation'])) {
            return $variable->variables_details['budget_validate_with_price_quotation'] == 1;
        }

        return false;
    }

    public function variableSettings()
    {
        $budget = $this->budget();
        $factoryId = $budget->factory_id;
        $buyerId = $budget->buyer_id;

        return MerchandisingVariableSettings::query()
            ->where([
                'factory_id' => $factoryId,
                'buyer_id' => $buyerId,
            ])->first();
    }
}
