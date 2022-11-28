<?php

namespace SkylarkSoft\GoRMG\GeneralStore\Interfaces;

interface StoreInterface
{
    public function stockInData();

    public function stockOutData();

    public function stockOutEditData();

    public function stockInEditData();
}
