<?php

namespace SkylarkSoft\GoRMG\Subcontract\Rules\SubTextileRules;

use Illuminate\Contracts\Validation\Rule;
use SkylarkSoft\GoRMG\Subcontract\Models\SubTextileModels\SubGreyStoreStockSummaryReport;

class StockCheckBeforeIssueRule extends QtyRuleCriteria implements Rule
{
    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Determine if the validation rule passes.
     *
     * @param string $attribute
     * @param mixed $value
     * @return bool
     */
    public function passes($attribute, $value): bool
    {
        return SubGreyStoreStockSummaryReport::query()->where($this->getCriteria())->exists();
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message(): string
    {
        return "Receive Doesn't Exists";
    }
}
