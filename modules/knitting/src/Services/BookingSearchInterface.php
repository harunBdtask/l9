<?php


namespace SkylarkSoft\GoRMG\Knitting\Services;

interface BookingSearchInterface
{
    public function search(array $data): array;
}