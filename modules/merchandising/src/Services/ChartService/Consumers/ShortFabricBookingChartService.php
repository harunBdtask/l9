<?php

namespace SkylarkSoft\GoRMG\Merchandising\Services\ChartService\Consumers;

use SkylarkSoft\GoRMG\Merchandising\Models\ShortBookings\ShortFabricBooking;
use SkylarkSoft\GoRMG\Merchandising\Services\ChartService\Concerns\ChartServiceContract;

class ShortFabricBookingChartService extends ChartServiceContract
{
    private $values = [];

    public function getLevels(): array
    {
        return [
            'Total Short Fabric Booking',
            'Approved',
            'UnApproved',
        ];
    }

    public function getValues(): array
    {
        if (!$this->values) {
            $totalShortFabricBooking = ShortFabricBooking::all()->count();
            $approved = ShortFabricBooking::query()->where('is_approved', 1)->count();
            $unApproved = ShortFabricBooking::query()
                ->where('is_approved', 0)
                ->orWhereNull('is_approved')
                ->count();

            $this->values = [
                $totalShortFabricBooking,
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
