<?php

namespace SkylarkSoft\GoRMG\DyesStore\Services;

use SkylarkSoft\GoRMG\DyesStore\Models\DsChemicalReceiveReturn;

class DyesChemicalReceiveReturnService
{
    public static function generateUniqueId(): string
    {
        $prefix = DsChemicalReceiveReturn::withTrashed()->max('id') + 1;
        $generate = str_pad($prefix, 5, "0", STR_PAD_LEFT);

        return getPrefix() . 'DCRR-' . date('y') . '-' . $generate;
    }
}
