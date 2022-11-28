<?php

namespace SkylarkSoft\GoRMG\Knitting\Services\Formatter;

interface RequisitionFormatter
{
    public function doFormat(RequisitionFormatAdapter $requisitionFormatAdapter): array;
}
