<?php

namespace SkylarkSoft\GoRMG\BasicFinance\Services\Reports;

class ReportViewService
{
    protected $factoryId;
    protected $fromDate;
    protected $toDate;
    protected $year;
    protected $month;
    protected $infoType;
    protected $requiredData = array();

    private $bindings = [
        'search_info' => SearchInfoViewService::class
    ];

    private function __construct($infoType)
    {
        $this->infoType = $infoType;
    }

    public static function for($infoType): ReportViewService
    {
        return new static($infoType);
    }

    /**
     * @return mixed
     */
    public function getFactoryId()
    {
        return $this->factoryId;
    }

    /**
     * @return mixed
     */
    public function getFromDate()
    {
        return $this->fromDate;
    }

    /**
     * @return mixed
     */
    public function getToDate()
    {
        return $this->toDate;
    }

    /**
     * @return mixed
     */
    public function getYear()
    {
        return $this->year;
    }

    /**
     * @return mixed
     */
    public function getMonth()
    {
        return $this->month;
    }

    /**
     * @return array
     */
    public function getRequiredData(): array
    {
        return $this->requiredData;
    }

    /**
     * @param mixed $factoryId
     */
    public function setFactoryId($factoryId): ReportViewService
    {
        $this->requiredData[] = 'factory';
        $this->factoryId = $factoryId;
        return $this;
    }

    /**
     * @param mixed $fromDate
     */
    public function setFromDate($fromDate): ReportViewService
    {
        $this->requiredData[] = 'from_date';
        $this->fromDate = $fromDate;
        return $this;
    }

    /**
     * @param mixed $toDate
     */
    public function setToDate($toDate): ReportViewService
    {
        $this->requiredData[] = 'to_date';
        $this->toDate = $toDate;
        return $this;
    }

    /**
     *
     * @param mixed $year
     */
    public function setYear($year): ReportViewService
    {
        $this->requiredData[] = 'year';
        $this->year = $year;
        return $this;
    }

    /**
     *
     * @param mixed $month
     */
    public function setMonth($month): ReportViewService
    {
        $this->requiredData[] = 'month';
        $this->month = $month;
        return $this;
    }

    public function getInfoType(): string
    {
        return $this->infoType;
    }

    public function render()
    {
        if (!isset($this->bindings[$this->getInfoType()])) {
            return null;
        }
        return (new $this->bindings[$this->getInfoType()])->generate(new RequiredDataService($this));
    }
}
