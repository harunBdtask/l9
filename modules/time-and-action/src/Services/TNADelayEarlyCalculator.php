<?php

namespace SkylarkSoft\GoRMG\TimeAndAction\Services;

use Carbon\Carbon;
use Illuminate\Support\Str;

class TNADelayEarlyCalculator
{

    private $planStartDate;
    private $actualStartDate;

    private $planFinishDate;
    private $actualFinishDate;

    public function __construct($task)
    {
        $this->planStartDate = $task->start_date;
        $this->planFinishDate = $task->finish_date;
        $this->actualStartDate = $task->actual_start_date;
        $this->actualFinishDate = $task->actual_finish_date;
    }

    public function delay(): string
    {
        $delay = '';
        $startDelays = $this->startDelay();
        $endDelays = $this->finishDelay();

        if ($startDelays > 0) {
            $delay .= 'S(' . $startDelays . ')';
        }

        if ($endDelays > 0) {
            $delay .= ' F(' . $endDelays . ')';
        }

        return trim($delay);
    }

    public function early(): string
    {
        $early = '';
        $startEarly = $this->startEarly();
        $finishEarly = $this->finishEarly();


        if ($startEarly > 0) {
            $early .= 'S(' . $startEarly . ')';
        }

        if ($finishEarly > 0) {
            $early .= ' F(' . $finishEarly . ')';
        }

        return trim($early);
    }

    public function startDelay(): int
    {
        if ($this->hasStartDates()) {
            return $this->getPlanStartDate()->diffInDays($this->getActualStartDate(), false);
        }

        return 0;
    }

    public function finishDelay(): int
    {
        if ($this->hasEndDates()) {
            return $this->getPlanFinishDate()->diffInDays($this->getActualFinishDate(), false);
        }

        return 0;
    }

    /**
     * Return early days in start date
     * @return int
     */
    public function startEarly(): int
    {
        if ($this->hasStartDates()) {
            return $this->getActualStartDate()->diffInDays($this->getPlanStartDate(), false);
        }

        return 0;
    }

    public function finishEarly(): int
    {
        if ($this->hasEndDates()) {
            return $this->getActualFinishDate()->diffInDays($this->getPlanFinishDate(), false);
        }

        return 0;
    }

    private function hasStartDates(): bool
    {
        return $this->planStartDate && $this->actualStartDate;
    }

    private function hasEndDates(): bool
    {
        return $this->planFinishDate && $this->actualFinishDate;
    }

    /**
     * @return Carbon
     */
    public function getPlanStartDate(): Carbon
    {
        return Carbon::parse($this->planStartDate);
    }

    /**
     * @return Carbon
     */
    public function getActualStartDate(): Carbon
    {
        return Carbon::parse($this->actualStartDate);
    }

    /**
     * @return Carbon
     */
    public function getPlanFinishDate(): Carbon
    {
        return Carbon::parse($this->planFinishDate);
    }

    /**
     * @return Carbon
     */
    public function getActualFinishDate(): Carbon
    {
        return Carbon::parse($this->actualFinishDate);
    }
}
