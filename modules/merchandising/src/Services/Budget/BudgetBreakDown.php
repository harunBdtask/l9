<?php


namespace SkylarkSoft\GoRMG\Merchandising\Services\Budget;

class BudgetBreakDown
{
    private $budgetId;
    private $itemId;
    private $rate_avg;
    private $cons_avg;
    private $dia_avg;
    private $process_avg;

    /**
     * @return mixed
     */
    public function getBudgetId()
    {
        return $this->budgetId;
    }

    /**
     * @param mixed $budgetId
     */
    public function setBudgetId($budgetId): void
    {
        $this->budgetId = $budgetId;
    }

    /**
     * @return mixed
     */
    public function getItemId()
    {
        return $this->itemId;
    }

    /**
     * @param mixed $itemId
     */
    public function setItemId($itemId): void
    {
        $this->itemId = $itemId;
    }

    /**
     * @return mixed
     */
    public function getRateAvg()
    {
        return $this->rate_avg;
    }

    /**
     * @param mixed $rate_avg
     */
    public function setRateAvg($rate_avg): void
    {
        $this->rate_avg = $rate_avg;
    }

    /**
     * @return mixed
     */
    public function getConsAvg()
    {
        return $this->cons_avg;
    }

    /**
     * @param mixed $cons_avg
     */
    public function setConsAvg($cons_avg): void
    {
        $this->cons_avg = $cons_avg;
    }

    /**
     * @return mixed
     */
    public function getDiaAvg()
    {
        return $this->dia_avg;
    }

    /**
     * @param mixed $dia_avg
     */
    public function setDiaAvg($dia_avg): void
    {
        $this->dia_avg = $dia_avg;
    }

    /**
     * @return mixed
     */
    public function getProcessAvg()
    {
        return $this->process_avg;
    }

    /**
     * @param mixed $process_avg
     */
    public function setProcessAvg($process_avg): void
    {
        $this->process_avg = $process_avg;
    }
}
