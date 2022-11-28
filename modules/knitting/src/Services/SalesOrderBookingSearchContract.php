<?php

namespace SkylarkSoft\GoRMG\Knitting\Services;

interface SalesOrderBookingSearchContract
{
    public function format(SalesOrderBookingSearch $salesOrderBookingSearch): array;
}
