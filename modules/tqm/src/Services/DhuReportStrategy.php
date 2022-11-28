<?php

namespace SkylarkSoft\GoRMG\TQM\Services;

class DhuReportStrategy
{
    const CUTTING = 'Cutting', SEWING = 'Sewing', FINISHING = 'Finishing';

    protected $type, $formDate, $toDate;

    protected $bindings = [
        self::CUTTING => CuttingDhuReportDataService::class,
        self::SEWING => SewingDhuReportDataService::class,
        self::FINISHING => FinishingDhuReportDataService::class,
    ];

    public function setType($type): DhuReportStrategy
    {
        $this->type = $type;
        return $this;
    }

    public function getType()
    {
        return $this->type;
    }

    public function setFromDate($formDate): DhuReportStrategy
    {
        $this->formDate = $formDate;
        return $this;
    }

    public function getFromDate()
    {
        return $this->formDate;
    }

    public function setToDate($toDate): DhuReportStrategy
    {
        $this->toDate = $toDate;
        return $this;
    }

    public function getToDate()
    {
        return $this->toDate;
    }

    public function generate()
    {
        if (!isset($this->bindings[$this->type])) {
            return [];
        }
        return (new $this->bindings[$this->type])->handle($this);
    }
}
