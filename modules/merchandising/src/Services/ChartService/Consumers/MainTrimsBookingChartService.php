<?php

namespace SkylarkSoft\GoRMG\Merchandising\Services\ChartService\Consumers;

use SkylarkSoft\GoRMG\Merchandising\Models\Bookings\TrimsBooking;
use SkylarkSoft\GoRMG\Merchandising\Services\ChartService\Concerns\ChartServiceContract;

class MainTrimsBookingChartService extends ChartServiceContract
{
    private $values = [];

    public function getLevels(): array
    {
        return [
            'Total Trims Booking',
            'Approved Trims Booking',
            'UnApproved Trims Booking',
        ];
    }

    public function getValues(): array
    {
        if (!$this->values) {
            $totalTrimsBooking = TrimsBooking::all()->count();
            $approved = TrimsBooking::query()->where('is_approve', 1)->count();
            $unApproved = TrimsBooking::query()
                ->where('is_approve', 0)
                ->orWhereNull('is_approve')
                ->count();

            $this->values = [
                $totalTrimsBooking,
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
