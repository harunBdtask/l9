<?php

namespace SkylarkSoft\GoRMG\Merchandising\Services\ChartService\Concerns;

abstract class ChartServiceContract
{
    const PIE = 'pie';
    const BAR = 'bar';
    const DONUT = 'donut';

    const COLORS = [
        "#FF8A65",
        "#FFB74D",
        "#81C784",
        "#4DB6AC",
        "#4FC3F7",
        "#5C6BC0",
        "#107884",
        "#FF7043",
        "#795548",
        "#CDDC39",
        "#607D8B",
        "#AB47BC"
    ];

    public function getColors(): array
    {
        $totalValues = count($this->getValues());
        return collect(self::COLORS)->shuffle()->take($totalValues)->toArray();
    }


    public function dashboardOverview(): array
    {
        return array_combine($this->getLevels(), $this->getValues());
    }


    abstract public function getLevels(): array;

    abstract public function getValues(): array;

    abstract public function renderIn(): string;

    abstract public function getType(): string;

}
