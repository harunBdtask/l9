<?php

namespace SkylarkSoft\GoRMG\TQM\Services;

interface DhuReportContract
{
    public function handle(DhuReportStrategy $strategy): array;
}
