<?php

namespace SkylarkSoft\GoRMG\Merchandising\Services\Report;

use Illuminate\Support\Str;
use SkylarkSoft\GoRMG\Knitting\Services\PlaningInfo\PlaningInfoObserverService;

class ReportViewService
{
    protected $infoType;
    protected $requiredData = array();

    protected $chartType;
    protected $chartLevel = array();
    protected $chartColor = array();
    protected $chartValues = array();

    private $bindings = [
        'chart'          => ChartViewService::class,
        'search_info'    => SearchInfoViewService::class
    ];

    private function __construct($infoType)
    {
        $this->infoType = $infoType;
        $this->chartColor = ["#FF8A65", "#FFB74D", "#81C784", "#4DB6AC", "#4FC3F7", "#5C6BC0", "#107884", "#FF7043", "#795548", "#CDDC39", "#607D8B", "#AB47BC"];
    }

    public static function for($infoType): ReportViewService
    {
        return new static($infoType);
    }


    /**
     * @param $method
     * @param $args
     * @return $this
     * @throws Exception
     */
    public function __call($method, $args)
    {
        $getSetterGetter = substr($method, 0, 3);
        $getMethodName = Str::snake(substr($method, 3));

        if ($getSetterGetter === 'set') {
            $this->requiredData[$getMethodName] = $args[0] ?? null;
        } else {
            return $this->requiredData[$getMethodName] ?? null;
        }

        return $this;
    }

    /**
     * @return array
     */
    public function getRequiredData(): array
    {
        return $this->requiredData;
    }

    public function getInfoType(): string
    {
        return $this->infoType;
    }


    /**
     * @return array
     */
    public function getChartLevel(): array
    {
        return $this->chartLevel;
    }

    /**
     * @param array $chartLevel
     * @return ReportViewService
     */
    public function setChartLevel(array $chartLevel): ReportViewService
    {
        $this->chartLevel = $chartLevel;
        return $this;
    }

    /**
     * @return array
     */
    public function getChartColor(): array
    {
        return $this->chartColor;
    }

    /**
     * @param array|string $chartColor
     */
    public function setChartColor($chartColor): ReportViewService
    {
        $this->chartColor = $chartColor;
        return $this;
    }


    public function getChartType()
    {
        return $this->chartType;
    }

    /**
     * @param $chartType
     * @return ReportViewService
     */
    public function setChartType($chartType): ReportViewService
    {
        $this->chartType = $chartType;
        return $this;
    }

    /**
     * @return array
     */
    public function getChartValues(): array
    {
        return $this->chartValues;
    }

    /**
     * @param array $chartValues
     * @return ReportViewService
     */
    public function setChartValues(array $chartValues): ReportViewService
    {
        $this->chartValues = $chartValues;
        return $this;
    }

    public function render()
    {
        if (!isset($this->bindings[$this->getInfoType()])) {
            return null;
        }
        return (new $this->bindings[$this->getInfoType()])->generate(new RequiredDataService(), $this);
    }
}
