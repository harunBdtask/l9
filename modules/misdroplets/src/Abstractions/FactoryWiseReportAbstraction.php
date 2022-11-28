<?php

namespace SkylarkSoft\GoRMG\Misdroplets\Abstractions;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Collection as SupportCollection;

abstract class FactoryWiseReportAbstraction
{
    protected $fromDate, $toDate, $factoryId, $query;

    public function getFromDate()
    {
        return $this->fromDate;
    }

    public function setFromDate($value): FactoryWiseReportAbstraction
    {
        $this->fromDate = $value;
        return $this;
    }

    public function getToDate()
    {
        return $this->toDate;
    }

    public function setToDate($value): FactoryWiseReportAbstraction
    {
        $this->toDate = $value;
        return $this;
    }

    public function getFactoryId()
    {
        return $this->factoryId;
    }

    public function setFactoryId($value = null): FactoryWiseReportAbstraction
    {
        $this->factoryId = $value;
        return $this;
    }

    abstract public function getData(): Collection;

    abstract public function fetch(): SupportCollection;

}
