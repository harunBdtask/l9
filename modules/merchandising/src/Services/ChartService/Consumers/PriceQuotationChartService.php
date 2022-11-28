<?php

namespace SkylarkSoft\GoRMG\Merchandising\Services\ChartService\Consumers;

use SkylarkSoft\GoRMG\Merchandising\Models\PriceQuotation;
use SkylarkSoft\GoRMG\Merchandising\Services\ChartService\Concerns\ChartServiceContract;

class PriceQuotationChartService extends ChartServiceContract
{

    private $values = [];

    public function getLevels(): array
    {
        return [
            'Total Price Quotation',
            'Approved Price Quotation',
            'UnApproved Price Quotation',
        ];
    }

    public function getValues(): array
    {
        if (!$this->values) {
            $TotalPriceQuotation = PriceQuotation::all()->count();
            $approved = PriceQuotation::query()->where('is_approve', 1)->count();
            $unApproved = PriceQuotation::query()
                ->where('is_approve', 0)
                ->orWhereNull('is_approve')
                ->count();
            $this->values = [
                $TotalPriceQuotation,
                $approved,
                $unApproved,
            ];
        }

        return $this->values;

    }

    public function renderIn(): string
    {
        return 'merchandising::chart-service.chart';
    }

    public function getType(): string
    {
        return self::BAR;
    }
}
