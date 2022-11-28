<?php

namespace SkylarkSoft\GoRMG\Merchandising\Services\ChartService\Consumers;

use SkylarkSoft\GoRMG\Merchandising\Models\ShortBookings\ShortTrimsBooking;
use SkylarkSoft\GoRMG\Merchandising\Services\ChartService\Concerns\ChartServiceContract;

class ShortTrimsBookingChartService extends ChartServiceContract
{

    public function getLevels(): array
    {
        return [
            'Total ShortTrims Booking',
            'Approved ShortTrims Booking',
            'UnApproved ShortTrims Booking',
        ];
    }

    public function getValues(): array
    {
        $totalShortTrimsBooking = ShortTrimsBooking::all()->count();
        $approved = ShortTrimsBooking::query()->where('is_approved', 1)->count();
        $unApproved = ShortTrimsBooking::query()
            ->where('is_approved', 0)
            ->orWhereNull('is_approved')
            ->count();

        return [
            $totalShortTrimsBooking,
            $approved,
            $unApproved,
        ];
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
