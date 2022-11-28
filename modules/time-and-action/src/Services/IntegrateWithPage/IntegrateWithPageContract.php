<?php

namespace SkylarkSoft\GoRMG\TimeAndAction\Services\IntegrateWithPage;

use Carbon\Carbon;

interface IntegrateWithPageContract
{
    public function actualDate(PageState $state): array;
}
