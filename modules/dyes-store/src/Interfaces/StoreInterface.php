<?php

namespace SkylarkSoft\GoRMG\DyesStore\Interfaces;

interface StoreInterface
{
    public function stockInData();

    public function stockOutData();

    public function stockOutEditData();

    public function stockInEditData();
}
