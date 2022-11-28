<?php

namespace SkylarkSoft\GoRMG\Commercial\Services\Report\PITrackingReport;

class PITrackingReportDetailsStrategy
{
    const FABRIC = 'main-fabric', TRIMS = 'main-trims', YARN = 'yarn-purchase';

    protected $detailsType;

    protected $implementors = [
        self::FABRIC => FabricWoDetails::class,
        self::TRIMS => TrimsWoDetails::class,
        self::YARN => YarnWoDetails::class,
    ];

    public function setStrategy($detailsType): PITrackingReportDetailsStrategy
    {
        $this->detailsType = $detailsType;
        return $this;
    }

    public function get($details)
    {
        if (!isset($this->implementors[$this->detailsType])) {
            return [];
        }
        return (new $this->implementors[$this->detailsType])->get($details);
    }

}
