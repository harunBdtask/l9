<?php

namespace SkylarkSoft\GoRMG\Merchandising\Services\Approval;

use Illuminate\Support\Collection;
use SkylarkSoft\GoRMG\Merchandising\Models\Budget;
use SkylarkSoft\GoRMG\SystemSettings\Models\MerchandisingVariableSettings;

class ApproveService
{
    private $collection;
    private $filterKey = 'is_approved';
    private $variable = false;

    private function __construct($collection)
    {
        $this->collection = $collection;
    }

    public function by($key): ApproveService
    {
        $this->filterKey = $key;
        return $this;
    }

    public static function checkFor($collection): ApproveService
    {
        return new static($collection);
    }

    private function filter()
    {

        if ($this->variable) {
            $this->collection = collect($this->collection)->filter(function ($item) {
                return $item[$this->filterKey];
            });
        } else {
            $this->collection = collect($this->collection);
        }

    }

    public function variableCheckByBudget($budgetId): ApproveService
    {

        $budget = $this->budget($budgetId);
        $factoryId = $budget->factory_id;
        $buyerId = $budget->buyer_id;

        $variableSettings = MerchandisingVariableSettings::query()
            ->where([
                'factory_id' => $factoryId,
                'buyer_id' => $buyerId,
            ])->first();
        if (isset($variableSettings->variables_details['po_approval_for_budget']) && $variableSettings->variables_details['po_approval_for_budget'] == 1) {
            $this->variable = true;
        }
        return $this;
    }

    private function budget($budgetId)
    {
        return Budget::query()->findOrFail($budgetId);
    }

    public function orderBy($column): ApproveService
    {
        $this->collection = collect($this->collection)->sortByDesc($column)->values();
        return $this;
    }

    public function get(): Collection
    {
        $this->filter();
        return $this->collection;
    }
}
