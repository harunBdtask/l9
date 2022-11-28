<?php

namespace SkylarkSoft\GoRMG\Merchandising\Services\ChartService\Consumers;

use SkylarkSoft\GoRMG\Merchandising\Models\Bookings\FabricBooking;
use SkylarkSoft\GoRMG\Merchandising\Services\ChartService\Concerns\ChartServiceContract;

class MainFabricBookingChartService extends ChartServiceContract
{
    private $values = [];

    public function getLevels(): array
    {
        return [
            'Total Fabric Booking',
            'Approved',
            'UnApproved',
        ];
    }

    public function getValues(): array
    {
        if (!$this->values) {
            $totalFabricBooking = FabricBooking::all()->count();
            $approved = FabricBooking::query()->where('is_approve', 1)->count();
            $unApproved = FabricBooking::query()
                ->where('is_approve', 0)
                ->orWhereNull('is_approve')
                ->count();

            $this->values = [
                $totalFabricBooking,
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
