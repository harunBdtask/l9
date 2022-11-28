<?php

namespace SkylarkSoft\GoRMG\Commercial\Services\Report\PITrackingReport;

use Illuminate\Support\Collection;

interface PITrackingDetailsInterface
{
    public function get($details): Collection;
}
