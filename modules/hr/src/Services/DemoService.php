<?php

namespace SkylarkSoft\GoRMG\HR\Services;

use Carbon\Carbon;
use SkylarkSoft\GoRMG\HR\Models\HrFastivalLeave;

class DemoService
{

    private $month;
    private $year;
    private $employee;
    protected $festivalLeaves;

    public function __construct($month, $year, $employee)
    {
        $this->month = $month;
        $this->year = $year;
        $this->employee = $employee;
    }

    /**
     * @return mixed
     */
    public function festivalLeaves()
    {

        if ($this->festivalLeaves) {
            return $this->festivalLeaves;
        }

        $date = Carbon::parse($this->year . '-' . $this->month . '-01');
        $start_date = $date->copy()->startOfMonth()->toDateString();
        $end_date = $date->copy()->endOfMonth()->toDateString();

        $this->festivalLeaves = HrFastivalLeave::whereDate('leave_date', '>=', $start_date)
            ->whereDate('leave_date', '<=', $end_date)
            ->pluck('leave_date')->toArray();
        return $this->festivalLeaves;
    }

    /**
     * @param mixed $month
     */
    public function setMonth($month): void
    {
        $this->festivalLeaves = null;
        $this->month = $month;
    }

    /**
     * @param mixed $year
     */
    public function setYear($year): void
    {
        $this->festivalLeaves = null;
        $this->year = $year;
    }

}
