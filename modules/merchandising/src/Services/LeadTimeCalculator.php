<?php

namespace SkylarkSoft\GoRMG\Merchandising\Services;

use Carbon\Carbon;
use SkylarkSoft\GoRMG\Knitting\Services\SalesOrderBookingSearch;

class LeadTimeCalculator
{
    private const LEAD_TIME_30_TO_44 = 15;
    private const LEAD_TIME_45_TO_59 = 18;
    private const LEAD_TIME_60_TO_74 = 21;
    private const LEAD_TIME_75_TO_90 = 25;
    private const LEAD_TIME_90_TO_ABOVE = 30;

    private $date;
    private $leadTime;

    public static function calculate(): LeadTimeCalculator
    {
        return new static();
    }

    /**
     * @param mixed $leadTime
     */
    public function setLeadTime($leadTime): LeadTimeCalculator
    {
        $this->leadTime = $leadTime;
        return $this;
    }

    /**
     * @param mixed $date
     */
    public function setDate($date): LeadTimeCalculator
    {
        $this->date = $date;
        return $this;
    }

    public function getProductionLeadTime(): int
    {
        $leadTime = $this->leadTime;
        if ($leadTime > 90) {
            $productionLeadTime = self::LEAD_TIME_90_TO_ABOVE;
        } elseif ($leadTime > 74) {
            $productionLeadTime = self::LEAD_TIME_75_TO_90;
        } elseif ($leadTime > 59) {
            $productionLeadTime = self::LEAD_TIME_60_TO_74;
        } elseif ($leadTime > 44) {
            $productionLeadTime = self::LEAD_TIME_45_TO_59;
        } else {
            $productionLeadTime = self::LEAD_TIME_30_TO_44;
        }

        return $productionLeadTime;
    }

    public function getExBomHandOverDate(): Carbon
    {
        return Carbon::parse($this->date)->subDays($this->getProductionLeadTime());
    }

    public function getPiBunchBudgetDate(): Carbon
    {
        return Carbon::parse($this->date)->addDays(7);
    }
}
